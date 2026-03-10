<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sqlFile = database_path('schema/consolidated-schema.sql');

        if (! file_exists($sqlFile)) {
            return;
        }

        $sql = file_get_contents($sqlFile);

        // Split on statement boundaries (semicolons followed by newlines)
        // and execute each statement separately
        $statements = array_filter(
            array_map('trim', preg_split('/;\s*\n/', $sql)),
            fn ($s) => $s !== ''
        );

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($statements as $statement) {
            if (! empty($statement)) {
                DB::unprepared($statement);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down(): void
    {
        // Drop all tables - handled by migrate:fresh
    }
};
