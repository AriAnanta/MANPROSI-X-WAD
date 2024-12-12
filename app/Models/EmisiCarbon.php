<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmisiCarbon extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_emisi_karbon',
        'kategori_emisi_karbon',
        'tanggal_emisi',
        'kadar_emisi_karbon',
        'deskripsi',
        'status',
        'kode_manager',
        'kode_user',
        'kode_admin'
    ];
    public function admin(){
        return $this->belongsTo(Admin::class, 'kode_admin'); 
    }
    public function manager(){
        return $this->belongsTo(Manager::class, 'kode_manager'); 
    }
    public function user(){
        return $this->belongsTo(Pengguna::class, 'kode_user'); 
    }

}
