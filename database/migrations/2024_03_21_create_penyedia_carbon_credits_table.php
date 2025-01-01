<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE TABLE penyedia_carbon_credits (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kode_penyedia VARCHAR(255) UNIQUE,
                nama_penyedia VARCHAR(255),
                deskripsi TEXT,
                harga_per_ton DECIMAL(10,2),
                mata_uang VARCHAR(10) DEFAULT 'IDR',
                is_active BOOLEAN DEFAULT true,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX (kode_penyedia)
            )
        ");
    }

    public function down()
    {
        DB::statement('DROP TABLE IF EXISTS penyedia_carbon_credits');
    }
}; 