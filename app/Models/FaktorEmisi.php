<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaktorEmisi extends Model
{
    protected $table = 'faktor_emisis';
    
    protected $fillable = [
        'kategori_emisi_karbon',
        'sub_kategori',
        'nilai_faktor',
        'satuan'
    ];
} 