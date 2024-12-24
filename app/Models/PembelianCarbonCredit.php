<?php

namespace App\Models;

class PembelianCarbonCredit
{
    public static function getTable()
    {
        return 'pembelian_carbon_credits';
    }

    public static function getColumns()
    {
        return [
            'kode_pembelian_carbon_credit',
            'jumlah_pembelian_carbon_credit',
            'tanggal_pembelian_carbon_credit',
            'bukti_pembelian',
            'deskripsi',
            'kode_manager',
            'kode_admin'
        ];
    }
}
