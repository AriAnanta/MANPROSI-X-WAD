<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KompensasiEmisi extends Model
{
    protected $table = 'kompensasi_emisi';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode_kompensasi',
        'kode_emisi_karbon',
        'jumlah_kompensasi',
        'tanggal_kompensasi',
        'status'
    ];

    protected $casts = [
        'jumlah_kompensasi' => 'decimal:2',
        'tanggal_kompensasi' => 'date'
    ];

    public function emisiCarbon()
    {
        return $this->belongsTo(EmisiCarbon::class, 'kode_emisi_karbon', 'kode_emisi_karbon');
    }
} 