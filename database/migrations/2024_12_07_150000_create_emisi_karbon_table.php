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
            $table->string('kode_manager')->nullable();
            $table->string('kode_user')->nullable();
            $table->string('kode_admin')->nullable();
            $table->timestamps();

            // Menambahkan foreign key constraints
            $table->foreign('kode_manager')->references('kode_manager')->on('managers')->onDelete('cascade');
            $table->foreign('kode_user')->references('kode_user')->on('penggunas')->onDelete('cascade');
            $table->foreign('kode_admin')->references('kode_admin')->on('admins')->onDelete('cascade');
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
