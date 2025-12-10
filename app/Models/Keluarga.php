<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
	protected $table = 'keluarga';
	public $timestamps = false;

	protected $casts = [
		'idkakitangan' => 'int',
		'hubungan' => 'int',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'idkakitangan',
		'nama',
		'hubungan',
		'kemaskini'
	];

	// 🔥 Relationship: keluarga belongs to staff
	public function kakitangan()
	{
		return $this->belongsTo(Kakitangan::class, 'idkakitangan');
	}

	// 🔥 Relationship: keluarga belongs to hubungan type
	public function hubunganInfo()
	{
		return $this->belongsTo(Hubungan::class, 'hubungan');
	}
}
