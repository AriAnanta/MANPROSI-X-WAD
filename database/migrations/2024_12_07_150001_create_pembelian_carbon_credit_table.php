<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE pembelian_carbon_credits (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kode_pembelian_carbon_credit VARCHAR(255) UNIQUE,
                jumlah_pembelian_carbon_credit DECIMAL(10,2),
                tanggal_pembelian_carbon_credit DATE,
                bukti_pembelian VARCHAR(255),
                deskripsi VARCHAR(255),
                kode_manager VARCHAR(255),
                kode_admin VARCHAR(255),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (kode_manager) REFERENCES managers(kode_manager) ON DELETE CASCADE,
                FOREIGN KEY (kode_admin) REFERENCES admins(kode_admin) ON DELETE CASCADE
            )
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS pembelian_carbon_credits');
    }
};
