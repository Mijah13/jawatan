<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MotoHariPekerja
 * 
 * @property int $id
 * @property int $tahun
 * @property string $moto
 * @property Carbon $kemaskini
 * @property int|null $pengguna
 *
 * @package App\Models
 */
class MotoHariPekerja extends Model
{
	protected $table = 'moto_hari_pekerja';
	public $timestamps = false;

	protected $casts = [
		'tahun' => 'int',
		'kemaskini' => 'datetime',
		'pengguna' => 'int'
	];

	protected $fillable = [
		'tahun',
		'moto',
		'kemaskini',
		'pengguna'
	];
}
