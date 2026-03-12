<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds the database with all data required for a fresh production install:
     * - Barcode label settings
     * - Permissions (roles & access control)
     * - World currencies
     * - Admin user, business, default location, contact, unit & roles
     *
     * After seeding, run: php artisan pharmacy:import-medicines
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BarcodesTableSeeder::class,
            PermissionsTableSeeder::class,
            CurrenciesTableSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
