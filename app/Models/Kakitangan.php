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
}
