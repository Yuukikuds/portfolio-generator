<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Messages sent from the footer "Feedback" box. Safe to run more than once (IF NOT EXISTS). */
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE IF NOT EXISTS feedback (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NULL REFERENCES users (id) ON DELETE SET NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
SQL);
    }

    public function down(): void
    {
        // Intentionally empty: we never drop collected feedback automatically.
    }
};
