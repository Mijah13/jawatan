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
        'tarikhlahir',
        'nofailperibadi',
        'jawatan',
        'gred',
        'nowaran',
        'penempatanwaran',
        'penempatanoperasi',
        'unit',
        'tarikhlantikanpertama',
        'tarikhlantikansekarang',
        'tarikhpengesahanjawatan',
        'tarikhmemangku',
        'tarikhnaikpangkat',
        'tarikhkeciast',
        'tarikhbertukarkeluar',
        'penempatanbaru',
        'hrmiskemaskini',
        'kodpenempatan',
        'level',
        'emel',
        'taraf_perkhidmatan',
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

    // Relationships for Dropdowns/Display
    public function jawatanRelation()
    {
        return $this->belongsTo(Jawatan::class, 'jawatan', 'id');
    }

    public function gredRelation()
    {
        return $this->belongsTo(Gred::class, 'gred', 'id');
    }

    public function penempatanWaranRelation()
    {
        return $this->belongsTo(Organisasi::class, 'penempatanwaran', 'id');
    }

    public function penempatanOperasiRelation()
    {
        return $this->belongsTo(Organisasi::class, 'penempatanoperasi', 'id');
    }

    public function unitRelation()
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }

    public function kodPenempatanRelation()
    {
        return $this->belongsTo(Penempatan::class, 'kodpenempatan', 'id');
    }
}
