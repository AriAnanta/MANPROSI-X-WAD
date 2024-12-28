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
                jumlah_kompensasi DECIMAL(10,2),
                sisa_carbon_credit DECIMAL(10,2),
                tanggal_pembelian_carbon_credit DATE,
                bukti_pembelian VARCHAR(255),
                deskripsi VARCHAR(255),
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                kode_manager VARCHAR(255),
                kode_admin VARCHAR(255),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (kode_manager) REFERENCES managers(kode_manager) ON DELETE CASCADE,
                FOREIGN KEY (kode_admin) REFERENCES admins(kode_admin) ON DELETE CASCADE
            )
        ");

        DB::statement("
            CREATE TABLE kompensasi_emisi (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kode_kompensasi VARCHAR(255) UNIQUE,
                kode_emisi_karbon VARCHAR(255),
                kode_pembelian_carbon_credit VARCHAR(255),
                jumlah_kompensasi DECIMAL(10,2),
                tanggal_kompensasi DATE,
                status ENUM('active', 'cancelled') DEFAULT 'active',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (kode_emisi_karbon) REFERENCES emisi_carbons(kode_emisi_karbon) ON DELETE CASCADE,
                FOREIGN KEY (kode_pembelian_carbon_credit) REFERENCES pembelian_carbon_credits(kode_pembelian_carbon_credit) ON DELETE CASCADE
            )
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS kompensasi_emisi');
        DB::statement('DROP TABLE IF EXISTS pembelian_carbon_credits');
    }
};
