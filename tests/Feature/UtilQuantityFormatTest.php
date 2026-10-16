<?php

namespace Tests\Feature;

use App\Utils\Util;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class UtilQuantityFormatTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        session()->put('business', ['quantity_precision' => 2]);
        session()->put('currency', ['decimal_separator' => '.', 'thousand_separator' => ',']);
    }

    public function test_it_drops_decimal_zeros_from_whole_quantities(): void
    {
        $util = app(Util::class);

        $this->assertSame('10', $util->formatQuantityWithoutTrailingZeros(10));
        $this->assertSame('2', $util->formatQuantityWithoutTrailingZeros('2.0000'));
        $this->assertSame('0', $util->formatQuantityWithoutTrailingZeros(0));
        $this->assertSame('1,500', $util->formatQuantityWithoutTrailingZeros(1500));
    }

    public function test_it_keeps_significant_fractional_digits(): void
    {
        $util = app(Util::class);

        $this->assertSame('2.5', $util->formatQuantityWithoutTrailingZeros(2.5));
        $this->assertSame('0.25', $util->formatQuantityWithoutTrailingZeros(0.25));
    }

    public function test_it_uses_business_details_separators(): void
    {
        $business_details = (object) [
            'quantity_precision' => 2,
            'currency_precision' => 2,
            'decimal_separator' => ',',
            'thousand_separator' => '.',
        ];

        $util = app(Util::class);

        $this->assertSame('1.000', $util->formatQuantityWithoutTrailingZeros(1000, $business_details));
        $this->assertSame('1.000,5', $util->formatQuantityWithoutTrailingZeros(1000.5, $business_details));
    }

    public function test_blade_directive_renders_trimmed_quantity(): void
    {
        $compiled = Blade::compileString('@format_quantity_trimmed($quantity)');

        $quantity = 50;
        ob_start();
        eval('?>'.$compiled);
        $this->assertSame('50', ob_get_clean());

        $quantity = 2.5;
        ob_start();
        eval('?>'.$compiled);
        $this->assertSame('2.5', ob_get_clean());
    }
}
