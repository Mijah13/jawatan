<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Kakitangan extends Authenticatable
{
    use Notifiable;

    protected $table = 'kakitangan';
    public $timestamps = false;

    protected $fillable = [
        'mykad',
        'nama',
        'katalaluan',
        'aktif',
    ];

    // Use "mykad" as username
    public function getAuthIdentifierName()
    {
        return 'mykad';
    }

    // VERY IMPORTANT: Return katalaluan instead of password
    public function getAuthPassword()
    {
        return $this->katalaluan;
    }

   // APC
    public function apc()
    {
        return $this->hasMany(Apc::class, 'id_kakitangan', 'id');
    }

    // Pencapaian
    public function pencapaian()
    {
        return $this->hasMany(Pencapaian::class, 'id_kakitangan', 'id');
    }

    // Pingat
    public function pingat()
    {
        return $this->hasMany(Pingat::class, 'id_kakitangan', 'id');
    }

    // Harta
    public function harta()
    {
        return $this->hasMany(Isyiharhartum::class, 'id_kakitangan', 'id');
    }
}
