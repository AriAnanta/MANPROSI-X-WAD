<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianCarbonCredit extends Model
{
    protected $table = 'pembelian_carbon_credits';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode_pembelian_carbon_credit',
        'kode_kompensasi',
        'jumlah_kompensasi',
        'tanggal_pembelian_carbon_credit',
        'bukti_pembelian',
        'deskripsi',
        'kode_admin'
    ];

    protected $casts = [
        'jumlah_kompensasi' => 'decimal:2',
        'tanggal_pembelian_carbon_credit' => 'date'
    ];

    public function kompensasiEmisi()
    {
        return $this->belongsTo(KompensasiEmisi::class, 'kode_kompensasi', 'kode_kompensasi');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'kode_manager', 'kode_manager');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'kode_admin', 'kode_admin');
    }
}
