<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Pengguna extends Model
{
    use HasFactory;

    protected $fillable = [
    'kode_user',
    'nama_user',
    'email',
    'password', 
    'no_telepon'
];

    public function emisi_carbon(){
        return $this->hasMany(EmisiCarbon::class,'kode_user');
    }
    public function notifikasi(){
        return $this->hasMany(Notifikasi::class,'kode_user');
}
}
