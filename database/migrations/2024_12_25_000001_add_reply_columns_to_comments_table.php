<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE comments 
            ADD COLUMN admin_reply TEXT NULL AFTER comment,
            ADD COLUMN manager_read BOOLEAN DEFAULT FALSE AFTER admin_reply
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE comments 
            DROP COLUMN admin_reply,
            DROP COLUMN manager_read
        ");
    }
}; 