<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PembelianCarbonCredit extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_pembelian_carbon_credit',
        'jumlah_pembelian_carbon_credit',
        'tanggal_pembelian_carbon_credit',
        'bukti_pembelian',
        'deskripsi',
        'kode_manager',
        'kode_admin'
    ];
    public function admin(){
        return $this->belongsTo(Admin::class, 'kode_admin'); 
    }
    public function manager(){
        return $this->belongsTo(Manager::class, 'kode_manager'); 
    }
}
