<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyediaCarbonCredit extends Model
{
    protected $table = 'penyedia_carbon_credits';
    
    protected $fillable = [
        'kode_penyedia',
        'nama_penyedia',
        'deskripsi',
        'harga_per_kg',
        'mata_uang',
        'is_active'
    ];

    public function pembelianCarbonCredits()
    {
        return $this->hasMany(PembelianCarbonCredit::class, 'kode_penyedia', 'kode_penyedia');
    }
} 