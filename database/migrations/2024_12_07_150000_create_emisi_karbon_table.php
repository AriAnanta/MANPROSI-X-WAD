<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emisi_carbons', function (Blueprint $table) {
            $table->id();
            $table->string('kode_emisi_karbon')->unique();
            $table->string('kategori_emisi_karbon');
            $table->date('tanggal_emisi');
            $table->decimal('kadar_emisi_karbon', 10, 2);
            $table->string('deskripsi');
            $table->string('status');
            $table->foreignId('kode_manager')->nullable()->constrained('managers')->onDelete('cascade');
            $table->foreignId('kode_user')->nullable()->constrained('penggunas')->onDelete('cascade');
            $table->foreignId('kode_admin')->nullable()->constrained('admins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emisi_carbons');
    }
};
