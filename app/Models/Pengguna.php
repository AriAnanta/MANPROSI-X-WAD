<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Pengguna extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    protected $fillable = [
    'kode_user',
    'nama_user',
    'email',
    'password', 
    'no_telepon'
];
    protected $hidden = [
    'password',
    'remember_token',
];

    public function emisi_carbon(){
        return $this->hasMany(EmisiCarbon::class,'kode_user');
    }
    public function notifikasi(){
        return $this->hasMany(Notifikasi::class,'kode_user');
}
}

