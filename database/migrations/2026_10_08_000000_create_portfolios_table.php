<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Creates the "portfolios" table from database/schema.sql.
     * The Node.js version of this project already created the same table in the
     * Railway PostgreSQL database, so the SQL uses IF NOT EXISTS and is safe to repeat.
     */
    public function up(): void
    {
        DB::unprepared(file_get_contents(database_path('schema.sql')));
    }

    public function down(): void
    {
        // Intentionally empty: the portfolios table is never dropped automatically.
    }
};
