<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Notifikasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_notifikasi',
        'kategori_notifikasi',
        'kode_admin',
        'kode_user',
        'tanggal',
        'deskripsi',
        
       
    ];
    public function user(){
        return $this->belongsTo(Pengguna::class, 'kode_user'); 
    }
    public function admin(){
        return $this->belongsTo(Pengguna::class, 'kode_admin'); 
    }
    
}
