<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            CREATE TABLE comments (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kode_pembelian_carbon_credit VARCHAR(255),
                kode_manager VARCHAR(255),
                comment TEXT,
                status ENUM('unread', 'read') DEFAULT 'unread',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (kode_pembelian_carbon_credit) 
                    REFERENCES pembelian_carbon_credits(kode_pembelian_carbon_credit) 
                    ON DELETE CASCADE,
                FOREIGN KEY (kode_manager) 
                    REFERENCES managers(kode_manager) 
                    ON DELETE CASCADE
            )
        ");
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS comments');
    }
};
