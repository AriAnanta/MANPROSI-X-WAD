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
            CREATE TABLE jobs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                queue VARCHAR(255),
                payload LONGTEXT,
                attempts TINYINT UNSIGNED,
                reserved_at INT UNSIGNED NULL,
                available_at INT UNSIGNED,
                created_at INT UNSIGNED,
                INDEX jobs_queue_index (queue)
            )
        ");

        DB::statement("
            CREATE TABLE job_batches (
                id VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255),
                total_jobs INT,
                pending_jobs INT,
                failed_jobs INT,
                failed_job_ids LONGTEXT,
                options MEDIUMTEXT NULL,
                cancelled_at INT NULL,
                created_at INT,
                finished_at INT NULL
            )
        ");

        DB::statement("
            CREATE TABLE failed_jobs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                uuid VARCHAR(255) UNIQUE,
                connection TEXT,
                queue TEXT,
                payload LONGTEXT,
                exception LONGTEXT,
                failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS failed_jobs');
        DB::statement('DROP TABLE IF EXISTS job_batches');
        DB::statement('DROP TABLE IF EXISTS jobs');
    }
};
