<?php

namespace Tests\Feature;

use App\Utils\Util;
use Tests\TestCase;

class UtilTimezoneTest extends TestCase
{
    public function test_it_formats_utc_datetimes_in_business_timezone(): void
    {
        session()->put('business', [
            'date_format' => 'm/d/Y',
            'time_format' => 24,
            'time_zone' => 'Asia/Dhaka',
        ]);

        $util = app(Util::class);

        $this->assertSame('03/27/2026 16:49', $util->format_date('2026-03-27 16:49:00', true));
        $this->assertSame('16:49', $util->format_time('2026-03-27 16:49:00'));
    }

    public function test_it_converts_explicit_utc_datetimes_to_business_timezone(): void
    {
        session()->put('business', [
            'date_format' => 'm/d/Y',
            'time_format' => 24,
            'time_zone' => 'Asia/Dhaka',
        ]);

        $util = app(Util::class);

        $this->assertSame('03/27/2026 16:42', $util->format_date('2026-03-27T10:42:00Z', true));
    }

    public function test_it_keeps_date_only_values_stable(): void
    {
        session()->put('business', [
            'date_format' => 'm/d/Y',
            'time_format' => 24,
            'time_zone' => 'Asia/Dhaka',
        ]);

        $util = app(Util::class);

        $this->assertSame('03/27/2026', $util->format_date('2026-03-27'));
    }
}
