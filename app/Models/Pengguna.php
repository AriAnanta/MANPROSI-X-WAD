<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'penggunas';
    
    protected $fillable = [
        'kode_user',
        'nama_user',
        'email',
        'password',
        'no_telepon'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getTable()
    {
        return $this->table;
    }

    public static function getColumns()
    {
        return [
            'kode_user',
            'nama_user',
            'email',
            'password',
            'no_telepon'
        ];
    }
}

