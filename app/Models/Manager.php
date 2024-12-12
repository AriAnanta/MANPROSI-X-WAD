<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Manager extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_manager',
        'nama_manager',
        'email',
        'password', 
        'no_telepon'
    ];
    public function emisi_carbon(){
        return $this->hasMany(EmisiCarbon::class,'kode_manager');
    }
    public function pembelian_carbon_credit(){
        return $this->hasMany(PembelianCarbonCredit::class,'kode_manager');
    }
}
