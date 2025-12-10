<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Latihan
 * 
 * @property int $id
 * @property string|null $tajuk
 * @property int|null $kategori
 * @property int|null $jenis
 * @property Carbon|null $mula
 * @property Carbon|null $tamat
 * @property int|null $tempoh
 * @property string|null $tempat
 * @property int|null $idkakitangan
 * @property string|null $penganjur
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Latihan extends Model
{
	protected $table = 'latihan';
	public $timestamps = false;

	protected $casts = [
		'kategori' => 'int',
		'jenis' => 'int',
		'mula' => 'datetime',
		'tamat' => 'datetime',
		'tempoh' => 'int',
		'idkakitangan' => 'int',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'tajuk',
		'kategori',
		'jenis',
		'mula',
		'tamat',
		'tempoh',
		'tempat',
		'idkakitangan',
		'penganjur',
		'kemaskini'
	];
}
