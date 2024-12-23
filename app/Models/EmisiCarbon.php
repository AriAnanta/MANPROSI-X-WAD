<?php

namespace App\Models;

class EmisiCarbon
{
    public static function getTable()
    {
        return 'emisi_carbons';
    }

    public static function getColumns()
    {
        return [
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
    }
}
