<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'kode_pembelian_carbon_credit',
        'kode_manager',
        'comment',
        'status',
        'admin_reply',
        'manager_read'
    ];

    public function pembelianCarbonCredit()
    {
        return $this->belongsTo(PembelianCarbonCredit::class, 'kode_pembelian_carbon_credit', 'kode_pembelian_carbon_credit');
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'kode_manager', 'kode_manager');
    }
} 