<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmisiCarbon extends Model
{
    protected $table = 'emisi_carbons';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode_emisi_karbon',
        'kategori_emisi_karbon',
        'sub_kategori',
        'nilai_aktivitas',
        'faktor_emisi',
        'deskripsi',
        'status',
        'kode_manager',
        'kode_user',
        'kode_admin',
        'tanggal_emisi'
    ];

    protected $casts = [
        'nilai_aktivitas' => 'decimal:2',
        'faktor_emisi' => 'decimal:4',
        'kadar_emisi_karbon' => 'decimal:2',
        'tanggal_emisi' => 'date'
    ];

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'kode_manager', 'kode_manager');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'kode_user', 'kode_user');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'kode_admin', 'kode_admin');
    }

    public function kompensasi()
    {
        return $this->hasMany(KompensasiEmisi::class, 'kode_emisi_karbon', 'kode_emisi_karbon');
    }
}