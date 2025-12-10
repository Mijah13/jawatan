<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SuratAkuanPerubatanPelulu
 * 
 * @property int $id
 * @property int $idkakitangan
 * @property Carbon $tarikh
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class SuratAkuanPerubatanPelulu extends Model
{
	protected $table = 'surat_akuan_perubatan_pelulus';
	public $timestamps = false;

	protected $casts = [
		'idkakitangan' => 'int',
		'tarikh' => 'datetime',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'idkakitangan',
		'tarikh',
		'kemaskini'
	];
}
