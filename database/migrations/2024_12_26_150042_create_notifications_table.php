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
            CREATE TABLE notifications (
                id CHAR(36) PRIMARY KEY,
                type VARCHAR(255) NOT NULL,
                title VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                priority VARCHAR(50) DEFAULT 'normal',
                status VARCHAR(50) DEFAULT 'unread',
                data JSON NULL,
                for_role VARCHAR(50) DEFAULT 'admin',
                notifiable_type VARCHAR(255) NULL,
                notifiable_id VARCHAR(255) NULL,
                read_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_notifiable (notifiable_type, notifiable_id),
                INDEX idx_type (type),
                INDEX idx_status (status),
                INDEX idx_for_role (for_role)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS notifications");
    }
};
