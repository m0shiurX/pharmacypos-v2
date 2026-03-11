<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('variations', function (Blueprint $table) {
            $table->enum('wholesale_discount_type', ['fixed', 'percentage'])->nullable()->after('sell_price_inc_tax');
            $table->decimal('wholesale_discount_amount', 22, 4)->default(0)->after('wholesale_discount_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variations', function (Blueprint $table) {
            $table->dropColumn(['wholesale_discount_type', 'wholesale_discount_amount']);
        });
    }
};
