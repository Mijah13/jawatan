<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPengesahanPelulus extends Model
{
    protected $table = 'surat_pengesahan_pelulus';
    public $timestamps = false;

    protected $fillable = [
        'idkakitangan',
        'tarikh',
    ];
}
