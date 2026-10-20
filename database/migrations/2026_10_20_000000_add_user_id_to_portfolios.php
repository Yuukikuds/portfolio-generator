<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Every portfolio now belongs to one user account.
     * Portfolios created before accounts existed keep user_id = NULL: nobody can open them,
     * and they are NOT deleted. Safe to run more than once (IF NOT EXISTS).
     */
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
ALTER TABLE portfolios
    ADD COLUMN IF NOT EXISTS user_id BIGINT NULL REFERENCES users (id) ON DELETE CASCADE;

CREATE INDEX IF NOT EXISTS idx_portfolios_user_id ON portfolios (user_id);
SQL);
    }

    public function down(): void
    {
        // Intentionally empty: we never drop user data automatically.
    }
};
