Schema::create('pembelian_carbon_credits', function (Blueprint $table) {
    $table->id();
    $table->string('kode_pembelian_carbon_credit')->unique();
    $table->string('kode_kompensasi');
    $table->decimal('jumlah_kompensasi', 10, 2);
    $table->date('tanggal_pembelian_carbon_credit');
    $table->string('bukti_pembelian');
    $table->text('deskripsi');
    $table->string('kode_admin');
    $table->timestamps();

    $table->foreign('kode_kompensasi')
          ->references('kode_kompensasi')
          ->on('kompensasi_emisi')
          ->onDelete('cascade');
    
    $table->foreign('kode_admin')
          ->references('kode_admin')
          ->on('admins')
          ->onDelete('cascade');
}); 