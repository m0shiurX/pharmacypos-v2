<?php

namespace Tests\Feature;

use App\Utils\ProductUtil;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FixStockMismatchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        config(['logging.default' => 'null']);

        $tables = [
            'business_locations' => ['business_id', 'name'],
            'products' => ['business_id', 'unit_id', 'name', 'type', 'sku', 'enable_stock'],
            'units' => ['short_name'],
            'product_variations' => ['name'],
            'variations' => ['product_id', 'product_variation_id', 'sub_sku', 'name', 'sell_price_inc_tax', 'deleted_at'],
            'variation_location_details' => ['variation_id', 'location_id', 'product_id', 'product_variation_id', 'qty_available'],
            'transactions' => ['location_id', 'type', 'status'],
            'purchase_lines' => ['transaction_id', 'variation_id', 'quantity', 'quantity_returned'],
            'transaction_sell_lines' => ['transaction_id', 'variation_id', 'quantity', 'quantity_returned', 'sub_unit_id'],
            'stock_adjustment_lines' => ['transaction_id', 'variation_id', 'quantity'],
        ];

        foreach ($tables as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($columns): void {
                $table->id();
                foreach ($columns as $column) {
                    $table->string($column)->nullable();
                }
                $table->timestamps();
            });
        }

        DB::table('business_locations')->insert(['id' => 1, 'business_id' => 1, 'name' => 'Test shop']);
        DB::table('products')->insert(['id' => 1, 'business_id' => 1, 'unit_id' => 1, 'name' => 'Test medicine', 'type' => 'single', 'sku' => 'TEST', 'enable_stock' => 1]);
        DB::table('units')->insert(['id' => 1, 'short_name' => 'piece']);
        DB::table('product_variations')->insert(['id' => 1, 'name' => 'Default']);
        DB::table('variations')->insert(['id' => 1, 'product_id' => 1, 'product_variation_id' => 1, 'name' => 'Default', 'sub_sku' => 'TEST', 'sell_price_inc_tax' => 1]);
        DB::table('variation_location_details')->insert(['id' => 1, 'variation_id' => 1, 'location_id' => 1, 'product_id' => 1, 'product_variation_id' => 1, 'qty_available' => 97]);
        $this->movement('opening_stock', 'received', 'purchase_lines', 100);
        $this->movement('sell', 'final', 'transaction_sell_lines', 90);
    }

    public function test_dry_run_reports_ten_pieces_without_changing_ninety_seven(): void
    {
        $this->assertSame(10.0, $this->calculatedStock());
        $this->artisan('pos:fixStockMismatch', ['--location' => 1])
            ->expectsTable(['Variation', 'Product', 'SKU', 'Current stock', 'Calculated stock', 'Difference'], [[1, 'Test medicine', 'TEST', 97, 10, 87]])
            ->assertExitCode(1);
        $this->assertSame(97.0, $this->availableStock());
    }

    public function test_apply_sets_ten_pieces_and_running_again_does_not_deduct_again(): void
    {
        $this->artisan('pos:fixStockMismatch', ['--location' => 1, '--apply' => true])->assertSuccessful();
        $this->assertSame(10.0, $this->availableStock());
        $this->artisan('pos:fixStockMismatch', ['--location' => 1, '--apply' => true])
            ->expectsOutput('Test shop (location 1): 0 product(s) with mismatched stock')->assertSuccessful();
        $this->assertSame(10.0, $this->availableStock());
    }

    public function test_saved_piece_quantity_is_not_multiplied_again_for_a_box_unit(): void
    {
        DB::table('transaction_sell_lines')->update(['sub_unit_id' => 2]);
        $this->assertSame(10.0, $this->calculatedStock());
    }

    public function test_box_counts_in_corrupt_invoice_data_cannot_be_repaired_by_this_command(): void
    {
        DB::table('transaction_sell_lines')->update(['quantity' => 3, 'sub_unit_id' => 2]);
        $this->artisan('pos:fixStockMismatch', ['--location' => 1, '--apply' => true])->assertSuccessful();
        $this->assertSame(97.0, $this->availableStock());
    }

    public function test_returns_transfers_adjustments_and_purchases_are_included(): void
    {
        DB::table('transaction_sell_lines')->update(['quantity_returned' => 30]);
        $this->movement('purchase', 'received', 'purchase_lines', 60, 10);
        $this->movement('purchase_return', 'final', 'purchase_lines', 0, 5);
        $this->movement('purchase_transfer', 'received', 'purchase_lines', 20);
        $this->movement('sell_transfer', 'final', 'transaction_sell_lines', 15);
        $this->movement('stock_adjustment', 'final', 'stock_adjustment_lines', 7);
        $this->movement('production_purchase', 'received', 'purchase_lines', 12);
        $this->movement('production_sell', 'final', 'transaction_sell_lines', 8);
        $this->assertSame(87.0, $this->calculatedStock());
        $this->artisan('pos:fixStockMismatch', ['--apply' => true])->assertSuccessful();
        $this->assertSame(87.0, $this->availableStock());
    }

    public function test_drafts_pending_purchases_and_other_locations_are_excluded(): void
    {
        $this->movement('sell', 'draft', 'transaction_sell_lines', 90);
        $this->movement('purchase', 'pending', 'purchase_lines', 100);
        $this->movement('sell', 'final', 'transaction_sell_lines', 90, 0, 2);
        $this->assertSame(10.0, $this->calculatedStock());
    }

    public function test_products_without_stock_tracking_are_not_changed(): void
    {
        DB::table('products')->update(['enable_stock' => 0]);
        $this->artisan('pos:fixStockMismatch', ['--apply' => true])->assertSuccessful();
        $this->assertSame(97.0, $this->availableStock());
    }

    public function test_fractional_base_unit_stock_is_preserved(): void
    {
        DB::table('transaction_sell_lines')->update(['quantity' => 90.5]);
        $this->artisan('pos:fixStockMismatch', ['--apply' => true])->assertSuccessful();
        $this->assertSame(9.5, $this->availableStock());
    }

    public function test_missing_location_stock_row_is_not_detected(): void
    {
        DB::table('variation_location_details')->delete();
        $this->assertCount(0, app(ProductUtil::class)->getVariationStockMisMatch(1, null, 1));
        $this->artisan('pos:fixStockMismatch', ['--apply' => true])->assertSuccessful();
        $this->assertDatabaseCount('variation_location_details', 0);
    }

    private function movement(string $type, string $status, string $table, float $quantity, float $returned = 0, int $locationId = 1): void
    {
        $transactionId = DB::table('transactions')->insertGetId(['location_id' => $locationId, 'type' => $type, 'status' => $status]);
        $line = ['transaction_id' => $transactionId, 'variation_id' => 1, 'quantity' => $quantity];
        if ($table !== 'stock_adjustment_lines') {
            $line['quantity_returned'] = $returned;
        }
        DB::table($table)->insert($line);
    }

    private function calculatedStock(): float
    {
        return (float) app(ProductUtil::class)->getVariationStockMisMatch(1, 1, 1)->first()->total_stock_calculated;
    }

    private function availableStock(): float
    {
        return (float) DB::table('variation_location_details')->where('id', 1)->value('qty_available');
    }
}
