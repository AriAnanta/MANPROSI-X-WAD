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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_notifikasi')->unique();
            $table->string('kategori_notifikasi');
            $table->string('deskripsi');
            $table->date('tanggal');
            $table->foreignId('kode_user')->constrained('penggunas')->onDelete('cascade');
            $table->foreignId('kode_admin')->constrained('admins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
