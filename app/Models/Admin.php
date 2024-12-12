<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'kode_admin',
        'nama_admin', 
        'email',
        'password',
        'no_telepon'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function emisi_carbon(){
        return $this->hasMany(EmisiCarbon::class,'kode_admin');
    }

    public function pembelian_carbon_credit(){
        return $this->hasMany(PembelianCarbonCredit::class,'kode_admin');
    }

    public function notifikasi(){
        return $this->hasMany(Notifikasi::class,'kode_admin');
    }
}
