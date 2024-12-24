<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable
{
    protected $table = 'managers';
    
    protected $fillable = [
        'kode_manager',
        'nama_manager',
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
            'kode_manager',
            'nama_manager',
            'email',
            'password',
            'no_telepon'
        ];
    }
}
