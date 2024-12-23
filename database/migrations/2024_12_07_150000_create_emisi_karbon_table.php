<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE emisi_carbons (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kode_emisi_karbon VARCHAR(255) UNIQUE,
                kategori_emisi_karbon VARCHAR(255),
                tanggal_emisi DATE,
                kadar_emisi_karbon DECIMAL(10,2),
                deskripsi VARCHAR(255),
                status VARCHAR(255),
                kode_manager VARCHAR(255),
                kode_user VARCHAR(255),
                kode_admin VARCHAR(255),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (kode_manager) REFERENCES managers(kode_manager) ON DELETE CASCADE,
                FOREIGN KEY (kode_user) REFERENCES penggunas(kode_user) ON DELETE CASCADE,
                FOREIGN KEY (kode_admin) REFERENCES admins(kode_admin) ON DELETE CASCADE
            )
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS emisi_carbons');
    }
};
