<?php

namespace Tests\Feature;

use App\Utils\ProductUtil;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class AdjustProductStockForInvoiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        session()->put('currency', [
            'thousand_separator' => ',',
            'decimal_separator' => '.',
        ]);
    }

    public function test_draft_to_final_decreases_stock_in_base_unit_for_sub_unit_lines(): void
    {
        $product_util = $this->partialProductUtil();
        $product_util->shouldReceive('decreaseProductQuantity')->once()->with(2696, 2696, 1, 60.0);

        $product_util->adjustProductStockForInvoice('draft', $this->transaction('final'), $this->input([
            $this->boxLine(['quantity' => '2.00', 'transaction_sell_lines_id' => 54767]),
        ]));
    }

    public function test_draft_to_final_keeps_quantity_for_base_unit_lines(): void
    {
        $product_util = $this->partialProductUtil();
        $product_util->shouldReceive('decreaseProductQuantity')->once()->with(2696, 2696, 1, 1200.0);

        $product_util->adjustProductStockForInvoice('draft', $this->transaction('final'), $this->input([
            $this->boxLine(['quantity' => '1,200.00', 'sub_unit_id' => 2, 'base_unit_multiplier' => 1]),
        ]));
    }

    public function test_draft_to_final_keeps_quantity_when_no_unit_is_posted(): void
    {
        $product_util = $this->partialProductUtil();
        $product_util->shouldReceive('decreaseProductQuantity')->once()->with(17242, 17242, 1, 4.0);

        $product_util->adjustProductStockForInvoice('draft', $this->transaction('final'), $this->input([
            ['product_id' => 17242, 'variation_id' => 17242, 'quantity' => '4.00'],
        ]));
    }

    public function test_final_to_final_decreases_only_new_lines_in_base_unit(): void
    {
        $product_util = $this->partialProductUtil();
        $product_util->shouldReceive('decreaseProductQuantity')->once()->with(12570, 12570, 1, 90.0);

        $product_util->adjustProductStockForInvoice('final', $this->transaction('final'), $this->input([
            $this->boxLine(['quantity' => '2.00', 'transaction_sell_lines_id' => 54767]),
            $this->boxLine(['product_id' => 12570, 'variation_id' => 12570, 'quantity' => '3.00']),
        ]));
    }

    public function test_final_to_draft_restores_stock_in_base_unit(): void
    {
        $product_util = $this->partialProductUtil();
        $product_util->shouldReceive('updateProductQuantity')->once()->with(1, 2696, 2696, 60.0, 0, null, false);

        $product_util->adjustProductStockForInvoice('final', $this->transaction('draft'), $this->input([
            $this->boxLine(['quantity' => '2.00', 'transaction_sell_lines_id' => 54767]),
        ]));
    }

    public function test_draft_to_draft_does_not_touch_stock(): void
    {
        $product_util = $this->partialProductUtil();
        $product_util->shouldNotReceive('decreaseProductQuantity');
        $product_util->shouldNotReceive('updateProductQuantity');

        $product_util->adjustProductStockForInvoice('draft', $this->transaction('draft'), $this->input([
            $this->boxLine(['quantity' => '2.00', 'transaction_sell_lines_id' => 54767]),
        ]));
    }

    private function partialProductUtil(): ProductUtil&MockInterface
    {
        return Mockery::mock(ProductUtil::class)->makePartial();
    }

    private function transaction(string $status): object
    {
        return (object) ['status' => $status];
    }

    /**
     * @param  array<int, array<string, mixed>>  $products
     * @return array{location_id: int, products: array<int, array<string, mixed>>}
     */
    private function input(array $products): array
    {
        return ['location_id' => 1, 'products' => $products];
    }

    /**
     * A sell row as posted by the sell screen: quantity in boxes of 30 pieces.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function boxLine(array $overrides = []): array
    {
        return array_merge([
            'product_id' => 2696,
            'variation_id' => 2696,
            'product_unit_id' => 2,
            'sub_unit_id' => 3,
            'base_unit_multiplier' => 30,
            'quantity' => '1.00',
        ], $overrides);
    }
}
