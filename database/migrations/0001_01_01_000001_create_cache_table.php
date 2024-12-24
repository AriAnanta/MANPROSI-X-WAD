<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE TABLE cache (
                `key` VARCHAR(255) PRIMARY KEY,
                value MEDIUMTEXT,
                expiration INT
            )
        ");

        DB::statement("
            CREATE TABLE cache_locks (
                `key` VARCHAR(255) PRIMARY KEY,
                owner VARCHAR(255),
                expiration INT
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS cache_locks');
        DB::statement('DROP TABLE IF EXISTS cache');
    }
};
