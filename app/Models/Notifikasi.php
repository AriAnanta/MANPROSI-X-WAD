<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis'; // Changed from 'notifikasi' to 'notifikasis'
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode_notifikasi',
        'kategori_notifikasi',
        'kode_admin',
        'kode_user',
        'tanggal',
        'deskripsi'
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'kode_user', 'kode_user');
    }
}