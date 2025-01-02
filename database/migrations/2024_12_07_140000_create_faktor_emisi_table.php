<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE faktor_emisis (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kategori_emisi_karbon VARCHAR(255),
                sub_kategori VARCHAR(255),
                nilai_faktor DECIMAL(10,2),
                satuan VARCHAR(50),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                UNIQUE KEY kategori_sub (kategori_emisi_karbon, sub_kategori)
            )
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS faktor_emisis');
    }
}; 