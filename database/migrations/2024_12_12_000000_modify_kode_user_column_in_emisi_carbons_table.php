<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE emisi_carbons 
            MODIFY COLUMN kode_user VARCHAR(255)
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE emisi_carbons 
            MODIFY COLUMN kode_user INT
        ");
    }
}; 