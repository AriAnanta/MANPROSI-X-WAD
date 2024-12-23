<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Authenticatable
{
    protected $table = 'admins';
    
    protected $fillable = [
        'kode_admin',
        'nama_admin', 
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
            'kode_admin',
            'nama_admin',
            'email',
            'password',
            'no_telepon'
        ];
    }
}
