<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE TABLE kompensasi_emisi (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kode_kompensasi VARCHAR(255) UNIQUE,
                kode_emisi_karbon VARCHAR(255),
                jumlah_kompensasi DECIMAL(10,2),
                tanggal_kompensasi DATE,
                status ENUM('pending', 'completed') DEFAULT 'pending',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (kode_emisi_karbon) REFERENCES emisi_carbons(kode_emisi_karbon) ON DELETE CASCADE
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS kompensasi_emisi');
    }
};
