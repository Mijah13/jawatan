<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratAkuanPerubatanPelulus extends Model
{
    protected $table = 'surat_akuan_perubatan_pelulus';
    public $timestamps = false;

    protected $fillable = [
        'idkakitangan',
        'tarikh',
    ];
}
