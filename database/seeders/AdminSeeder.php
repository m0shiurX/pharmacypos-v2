<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $password = Hash::make('123456');
        $now = Carbon::now()->toDateTimeString();

        $shortcuts = '{"pos":{"express_checkout":"shift+e","pay_n_ckeckout":"shift+p","draft":"shift+d","cancel":"shift+c","edit_discount":"shift+i","edit_order_tax":"shift+t","add_payment_row":"shift+r","finalize_payment":"shift+f","recent_product_quantity":"f2","add_new_product":"f4"}}';
        $prefixes = '{"purchase":"PO","stock_transfer":"ST","stock_adjustment":"SA","sell_return":"CN","expense":"EP","contacts":"CO","purchase_payment":"PP","sell_payment":"SP","business_location":"BL"}';

        // Create business
        DB::table('business')->insert([
            'id' => 1,
            'name' => 'PharmacyPOS',
            'currency_id' => 2, // USD
            'start_date' => Carbon::today()->format('Y-m-d'),
            'tax_number_1' => null,
            'tax_label_1' => null,
            'default_profit_percent' => 25,
            'owner_id' => 1,
            'time_zone' => 'Asia/Dhaka',
            'fy_start_month' => 1,
            'accounting_method' => 'fifo',
            'sell_price_tax' => 'includes',
            'sku_prefix' => 'PP',
            'enable_product_expiry' => 1,
            'expiry_type' => 'add_manufacturing',
            'on_product_expiry' => 'stop_selling',
            'stop_selling_before' => 0,
            'enable_tooltip' => 1,
            'transaction_edit_days' => 30,
            'stock_expiry_alert_days' => 30,
            'keyboard_shortcuts' => $shortcuts,
            'enable_brand' => 1,
            'enable_category' => 1,
            'enable_sub_category' => 1,
            'enable_price_tax' => 1,
            'enable_purchase_status' => 1,
            'enable_lot_number' => 1,
            'enable_editing_product_from_purchase' => 1,
            'item_addition_method' => 1,
            'enable_inline_tax' => 1,
            'currency_symbol_placement' => 'before',
            'enabled_modules' => '["purchases","add_sale","pos_sale","stock_transfers","stock_adjustment","expenses","account"]',
            'date_format' => 'm/d/Y',
            'time_format' => '24',
            'ref_no_prefixes' => $prefixes,
            'weighing_scale_setting' => '{}',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create invoice scheme
        DB::table('invoice_schemes')->insert([
            'id' => 1,
            'business_id' => 1,
            'name' => 'Default',
            'scheme_type' => 'blank',
            'prefix' => 'PP',
            'start_number' => 1,
            'invoice_count' => 0,
            'total_digits' => 4,
            'is_default' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create invoice layout
        DB::table('invoice_layouts')->insert([
            'id' => 1,
            'name' => 'Default',
            'business_id' => 1,
            'is_default' => 1,
            'show_business_name' => 1,
            'show_location_name' => 1,
            'show_landmark' => 1,
            'show_city' => 1,
            'show_state' => 1,
            'show_zip_code' => 1,
            'show_country' => 1,
            'show_mobile_number' => 1,
            'show_tax_1' => 1,
            'show_customer' => 1,
            'show_payments' => 1,
            'show_sku' => 1,
            'show_cat_code' => 1,
            'show_time' => 1,
            'table_product_label' => 'Product',
            'table_qty_label' => 'Quantity',
            'table_unit_price_label' => 'Unit Price',
            'table_subtotal_label' => 'Subtotal',
            'sub_total_label' => 'Subtotal',
            'discount_label' => 'Discount',
            'tax_label' => 'Tax',
            'total_label' => 'Total',
            'total_due_label' => 'Total Due',
            'paid_label' => 'Total Paid',
            'customer_label' => 'Customer',
            'invoice_no_prefix' => 'Invoice No.',
            'invoice_heading' => 'Invoice',
            'highlight_color' => '#000000',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create business location
        DB::table('business_locations')->insert([
            'id' => 1,
            'business_id' => 1,
            'name' => 'Main Store',
            'landmark' => '',
            'country' => 'BD',
            'state' => '',
            'city' => 'Dhaka',
            'zip_code' => '1000',
            'invoice_scheme_id' => 1,
            'invoice_layout_id' => 1,
            'print_receipt_on_invoice' => 1,
            'receipt_printer_type' => 'browser',
            'is_active' => 1,
            'default_payment_accounts' => '{"cash":{"is_enabled":"1","account":null},"card":{"is_enabled":"1","account":null},"cheque":{"is_enabled":"1","account":null},"bank_transfer":{"is_enabled":"1","account":null},"other":{"is_enabled":"1","account":null},"custom_pay_1":{"is_enabled":"1","account":null},"custom_pay_2":{"is_enabled":"1","account":null},"custom_pay_3":{"is_enabled":"1","account":null}}',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create admin user
        DB::table('users')->insert([
            'id' => 1,
            'surname' => 'Mr',
            'first_name' => 'Admin',
            'last_name' => null,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => $password,
            'language' => 'en',
            'business_id' => 1,
            'is_cmmsn_agnt' => 0,
            'cmmsn_percent' => '0.00',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create default Walk-In Customer contact
        DB::table('contacts')->insert([
            'id' => 1,
            'business_id' => 1,
            'type' => 'customer',
            'name' => 'Walk-In Customer',
            'city' => 'Dhaka',
            'state' => '',
            'country' => 'BD',
            'mobile' => '',
            'created_by' => 1,
            'is_default' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create default unit
        DB::table('units')->insert([
            'id' => 1,
            'business_id' => 1,
            'actual_name' => 'Pieces',
            'short_name' => 'Pc(s)',
            'allow_decimal' => 0,
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Create Admin role and assign to user
        $admin_role = Role::create([
            'name' => 'Admin#1',
            'business_id' => 1,
            'guard_name' => 'web',
            'is_default' => 1,
        ]);

        $cashier_role = Role::create([
            'name' => 'Cashier#1',
            'business_id' => 1,
            'guard_name' => 'web',
            'is_default' => 1,
        ]);

        Permission::create(['name' => 'location.1', 'guard_name' => 'web']);

        $admin = User::findOrFail(1);
        $admin->assignRole('Admin#1');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
