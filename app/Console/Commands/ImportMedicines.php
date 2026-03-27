<?php

namespace App\Console\Commands;

use App\Brands;
use App\Business;
use App\BusinessLocation;
use App\Category;
use App\Product;
use App\ProductVariation;
use App\Unit;
use App\Variation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportMedicines extends Command
{
    protected $signature = 'pharmacy:import-medicines
                            {--file= : Path to CSV file (default: app/docs/import_products_ready.csv)}
                            {--business_id=1 : Business ID to import into}';

    protected $description = 'Import medicines from CSV into the products table (CLI-based, supports 20K+ rows)';

    public function handle()
    {
        $file = $this->option('file') ?: base_path('app/docs/import_products_ready.csv');
        $business_id = (int) $this->option('business_id');

        if (! file_exists($file)) {
            $this->error("CSV file not found: {$file}");

            return 1;
        }

        $business = Business::find($business_id);
        if (! $business) {
            $this->error("Business with ID {$business_id} not found. Run migrations and seeders first.");

            return 1;
        }

        $sku_prefix = $business->sku_prefix ?? 'PP';
        $locations = BusinessLocation::where('business_id', $business_id)->pluck('id')->toArray();

        if (empty($locations)) {
            $this->error('No business locations found. Run seeders first.');

            return 1;
        }

        // Check if products already exist for this business
        $existing_count = Product::where('business_id', $business_id)->count();
        if ($existing_count > 0) {
            if (! $this->confirm("There are already {$existing_count} products for this business. Continue importing? (duplicates will be skipped by SKU)")) {
                return 0;
            }
        }

        $this->info("Reading CSV: {$file}");

        $handle = fopen($file, 'r');
        if (! $handle) {
            $this->error('Cannot open CSV file.');

            return 1;
        }

        // Skip header row
        $header = fgetcsv($handle);

        // Count rows for progress bar
        $total_rows = 0;
        while (fgetcsv($handle) !== false) {
            $total_rows++;
        }
        rewind($handle);
        fgetcsv($handle); // skip header again

        $this->info("Found {$total_rows} rows to process.");

        // Cache lookups to avoid repeated queries
        $unit_cache = [];
        $brand_cache = [];
        $category_cache = [];
        $existing_skus = Product::where('business_id', $business_id)
            ->pluck('sku')
            ->flip()
            ->toArray();

        $bar = $this->output->createProgressBar($total_rows);
        $bar->start();

        $imported = 0;
        $skipped = 0;
        $errors = [];

        // Process in chunks using DB transaction batches
        $chunk_size = 500;
        $chunk = [];
        $row_num = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $row_num++;

            // Need at least 37 columns
            if (count($row) < 37) {
                $errors[] = "Row {$row_num}: insufficient columns (".count($row).')';
                $bar->advance();

                continue;
            }

            $product_name = trim($row[0]);
            if (empty($product_name)) {
                $bar->advance();
                $skipped++;

                continue;
            }

            $chunk[] = ['row' => $row, 'row_num' => $row_num];

            if (count($chunk) >= $chunk_size) {
                $result = $this->processChunk(
                    $chunk,
                    $business_id,
                    $sku_prefix,
                    $locations,
                    $unit_cache,
                    $brand_cache,
                    $category_cache,
                    $existing_skus,
                    $errors
                );
                $imported += $result['imported'];
                $skipped += $result['skipped'];
                $unit_cache = $result['unit_cache'];
                $brand_cache = $result['brand_cache'];
                $category_cache = $result['category_cache'];
                $existing_skus = $result['existing_skus'];
                $chunk = [];
                $bar->advance($chunk_size);
            }
        }

        // Process remaining
        if (! empty($chunk)) {
            $remaining = count($chunk);
            $result = $this->processChunk(
                $chunk,
                $business_id,
                $sku_prefix,
                $locations,
                $unit_cache,
                $brand_cache,
                $category_cache,
                $existing_skus,
                $errors
            );
            $imported += $result['imported'];
            $skipped += $result['skipped'];
            $bar->advance($remaining);
        }

        fclose($handle);
        $bar->finish();
        $this->newLine(2);

        $this->info("Import complete: {$imported} imported, {$skipped} skipped.");

        if (! empty($errors)) {
            $this->warn('Errors ('.count($errors).'):');
            foreach (array_slice($errors, 0, 20) as $err) {
                $this->line("  - {$err}");
            }
            if (count($errors) > 20) {
                $this->line('  ... and '.(count($errors) - 20).' more.');
            }
        }

        return 0;
    }

    private function processChunk(
        array $chunk,
        int $business_id,
        string $sku_prefix,
        array $locations,
        array $unit_cache,
        array $brand_cache,
        array $category_cache,
        array $existing_skus,
        array &$errors
    ): array {
        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();

        try {
            foreach ($chunk as $item) {
                $row = $item['row'];
                $row_num = $item['row_num'];

                $product_name = trim($row[0]);
                $brand_name = trim($row[1]);
                $unit_name = trim($row[2]);
                $category_name = trim($row[3]);
                $sub_category_name = trim($row[4]);
                $sku = trim($row[5] ?? '');
                $enable_stock = trim($row[7]);
                $tax_type = strtolower(trim($row[12]));
                $product_type = strtolower(trim($row[13]));
                $dpp_exc_tax = trim($row[18] ?? '');
                $selling_price = trim($row[20] ?? '');
                $product_description = trim($row[30] ?? '');
                $custom_field1 = trim($row[31] ?? '');
                $custom_field2 = trim($row[32] ?? '');
                $custom_field3 = trim($row[33] ?? '');
                $custom_field4 = trim($row[34] ?? '');
                $not_for_selling = (! empty($row[35]) && $row[35] == 1) ? 1 : 0;

                // Only handle single product type
                if ($product_type !== 'single') {
                    $skipped++;

                    continue;
                }

                // Resolve unit
                if (empty($unit_name)) {
                    $errors[] = "Row {$row_num}: UNIT is required";
                    $skipped++;

                    continue;
                }

                $unit_key = strtolower($unit_name);
                if (! isset($unit_cache[$unit_key])) {
                    $unit = Unit::where('business_id', $business_id)
                        ->where(function ($q) use ($unit_name) {
                            $q->whereRaw('LOWER(short_name) = ?', [strtolower($unit_name)])
                                ->orWhereRaw('LOWER(actual_name) = ?', [strtolower($unit_name)]);
                        })->first();

                    if (! $unit) {
                        // Auto-create unit
                        $unit = Unit::create([
                            'business_id' => $business_id,
                            'actual_name' => ucfirst($unit_name),
                            'short_name' => $unit_name,
                            'allow_decimal' => 0,
                            'created_by' => 1,
                        ]);
                    }
                    $unit_cache[$unit_key] = $unit->id;
                }

                // Resolve brand
                $brand_id = null;
                if (! empty($brand_name)) {
                    $brand_key = strtolower($brand_name);
                    if (! isset($brand_cache[$brand_key])) {
                        $brand = Brands::firstOrCreate(
                            ['business_id' => $business_id, 'name' => $brand_name],
                            ['created_by' => 1]
                        );
                        $brand_cache[$brand_key] = $brand->id;
                    }
                    $brand_id = $brand_cache[$brand_key];
                }

                // Resolve category
                $category_id = null;
                if (! empty($category_name)) {
                    $cat_key = strtolower($category_name);
                    if (! isset($category_cache[$cat_key])) {
                        $category = Category::firstOrCreate(
                            ['business_id' => $business_id, 'name' => $category_name, 'category_type' => 'product'],
                            ['created_by' => 1, 'parent_id' => 0]
                        );
                        $category_cache[$cat_key] = $category->id;
                    }
                    $category_id = $category_cache[$cat_key];
                }

                // Resolve sub-category
                $sub_category_id = null;
                if (! empty($sub_category_name) && $category_id) {
                    $sub_key = strtolower($sub_category_name).'_'.$category_id;
                    if (! isset($category_cache[$sub_key])) {
                        $sub_category = Category::firstOrCreate(
                            ['business_id' => $business_id, 'name' => $sub_category_name, 'category_type' => 'product'],
                            ['created_by' => 1, 'parent_id' => $category_id]
                        );
                        $category_cache[$sub_key] = $sub_category->id;
                    }
                    $sub_category_id = $category_cache[$sub_key];
                }

                // Parse price
                $dpp = ! empty($dpp_exc_tax) ? (float) $dpp_exc_tax : 0;
                $sp = ! empty($selling_price) ? (float) $selling_price : $dpp;

                // Tax type
                if (! in_array($tax_type, ['inclusive', 'exclusive'])) {
                    $tax_type = 'exclusive';
                }

                // Create product
                $product = Product::create([
                    'name' => $product_name,
                    'business_id' => $business_id,
                    'type' => 'single',
                    'unit_id' => $unit_cache[$unit_key],
                    'brand_id' => $brand_id,
                    'category_id' => $category_id,
                    'sub_category_id' => $sub_category_id,
                    'tax_type' => $tax_type,
                    'enable_stock' => in_array($enable_stock, ['0', '1']) ? (int) $enable_stock : 1,
                    'alert_quantity' => trim($row[8]) ?: null,
                    'sku' => ' ', // placeholder, will be generated
                    'barcode_type' => 'C128',
                    'not_for_selling' => $not_for_selling,
                    'product_description' => $product_description ?: null,
                    'product_custom_field1' => $custom_field1,
                    'product_custom_field2' => $custom_field2,
                    'product_custom_field3' => $custom_field3,
                    'product_custom_field4' => $custom_field4,
                    'enable_sr_no' => 0,
                    'created_by' => 1,
                ]);

                // Generate SKU
                $generated_sku = $sku_prefix.str_pad($product->id, 4, '0', STR_PAD_LEFT);
                $product->sku = $generated_sku;
                $product->save();
                $existing_skus[$generated_sku] = true;

                // Assign all business locations
                $product->product_locations()->sync($locations);

                // Create product variation (DUMMY for single products)
                $product_variation = ProductVariation::create([
                    'name' => 'DUMMY',
                    'product_id' => $product->id,
                    'is_dummy' => 1,
                ]);

                // Create variation with pricing
                Variation::create([
                    'name' => 'DUMMY',
                    'product_id' => $product->id,
                    'product_variation_id' => $product_variation->id,
                    'sub_sku' => $generated_sku,
                    'default_purchase_price' => $dpp,
                    'dpp_inc_tax' => $dpp, // no tax on these medicines
                    'profit_percent' => 0,
                    'default_sell_price' => $sp,
                    'sell_price_inc_tax' => $sp,
                ]);

                $imported++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $errors[] = "Chunk error: {$e->getMessage()}";
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'unit_cache' => $unit_cache,
            'brand_cache' => $brand_cache,
            'category_cache' => $category_cache,
            'existing_skus' => $existing_skus,
        ];
    }
}
