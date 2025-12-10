<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SuratAkuanPerubatan
 * 
 * @property int $id
 * @property int $idkakitangan
 * @property string $hospital
 * @property string $no_rujukan
 * @property string $wad
 * @property int|null $pesakit
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class SuratAkuanPerubatan extends Model
{
	protected $table = 'surat_akuan_perubatan';
	public $timestamps = false;

	protected $casts = [
		'idkakitangan' => 'int',
		'pesakit' => 'int',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'idkakitangan',
		'hospital',
		'no_rujukan',
		'wad',
		'pesakit',
		'kemaskini'
	];
}
