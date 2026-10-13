<?php

namespace App\Console\Commands;

use App\BusinessLocation;
use App\Utils\ProductUtil;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixStockMismatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:fixStockMismatch
                            {--location= : Only check this business location id}
                            {--apply : Write the calculated stock, without this option nothing is changed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists products whose stock on hand differs from the stock calculated from purchases, sales, returns and adjustments, and optionally corrects it';

    public function __construct(protected ProductUtil $productUtil)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $locations = BusinessLocation::when($this->option('location'), function ($query, $location_id) {
            $query->where('id', $location_id);
        })->get();

        $total_mismatches = 0;
        foreach ($locations as $location) {
            $mismatches = $this->getMismatches($location);
            $total_mismatches += $mismatches->count();

            $this->info("{$location->name} (location {$location->id}): {$mismatches->count()} product(s) with mismatched stock");

            if ($mismatches->isEmpty()) {
                continue;
            }

            $this->table(
                ['Variation', 'Product', 'SKU', 'Current stock', 'Calculated stock', 'Difference'],
                $mismatches->map(fn ($row) => [
                    $row->variation_id,
                    $row->product,
                    $row->sub_sku,
                    (float) $row->stock,
                    (float) $row->total_stock_calculated,
                    (float) $row->stock - (float) $row->total_stock_calculated,
                ])->all()
            );

            if ($this->option('apply')) {
                $this->applyCalculatedStock($location, $mismatches);
            }
        }

        if ($total_mismatches > 0 && ! $this->option('apply')) {
            $this->warn('Dry run, nothing was changed. Run again with --apply to correct the stock.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * Stock enabled variations whose stock on hand differs from the calculated stock.
     */
    private function getMismatches(BusinessLocation $location): Collection
    {
        return $this->productUtil
            ->getVariationStockMisMatch($location->business_id, null, $location->id)
            ->filter(fn ($row) => $row->enable_stock == 1
                && abs((float) $row->stock - (float) $row->total_stock_calculated) > 0.0001)
            ->values();
    }

    private function applyCalculatedStock(BusinessLocation $location, Collection $mismatches): void
    {
        DB::transaction(function () use ($location, $mismatches) {
            foreach ($mismatches as $row) {
                $this->productUtil->fixVariationStockMisMatch(
                    $location->business_id,
                    $row->variation_id,
                    $location->id,
                    $row->total_stock_calculated
                );

                Log::info('pos:fixStockMismatch corrected stock', [
                    'location_id' => $location->id,
                    'variation_id' => $row->variation_id,
                    'product' => $row->product,
                    'stock_before' => (float) $row->stock,
                    'stock_after' => (float) $row->total_stock_calculated,
                ]);
            }
        });

        $this->info("Corrected {$mismatches->count()} product(s) at {$location->name}.");
    }
}
