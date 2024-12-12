<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Manager extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    protected $fillable = [
        'kode_manager',
        'nama_manager',
        'email',
        'password', 
        'no_telepon'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function emisi_carbon(){
        return $this->hasMany(EmisiCarbon::class,'kode_manager');
    }
    public function pembelian_carbon_credit(){
        return $this->hasMany(PembelianCarbonCredit::class,'kode_manager');
    }
}
