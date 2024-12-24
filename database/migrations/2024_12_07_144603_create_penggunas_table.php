<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE penggunas (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kode_user VARCHAR(255) UNIQUE,
                nama_user VARCHAR(255),
                email VARCHAR(255) UNIQUE,
                password VARCHAR(255),
                no_telepon VARCHAR(255),
                remember_token VARCHAR(100),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS penggunas');
    }
};
