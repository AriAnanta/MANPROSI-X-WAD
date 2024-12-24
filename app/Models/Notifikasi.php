<?php

namespace App\Models;

class Notifikasi
{
    public static function getTable()
    {
        return 'notifikasis';
    }

    public static function getColumns()
    {
        return [
            'kode_notifikasi',
            'kategori_notifikasi',
            'kode_admin',
            'kode_user',
            'tanggal',
            'deskripsi'
        ];
    }
}
