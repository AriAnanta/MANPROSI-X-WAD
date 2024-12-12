<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('emisi_carbons', function (Blueprint $table) {
            $table->string('kode_user')->change();
        });
    }

    public function down()
    {
        Schema::table('emisi_carbons', function (Blueprint $table) {
            $table->integer('kode_user')->change();
        });
    }
}; 