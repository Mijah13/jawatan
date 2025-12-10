<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GajiPokok
 * 
 * @property int $id
 * @property int $idkakitangan
 * @property string|null $no_gaji
 * @property float $gaji_pokok
 * @property string|null $gred_gaji
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class GajiPokok extends Model
{
	protected $table = 'gaji_pokok';
	public $timestamps = false;

	protected $casts = [
		'idkakitangan' => 'int',
		'gaji_pokok' => 'float',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'idkakitangan',
		'no_gaji',
		'gaji_pokok',
		'gred_gaji',
		'kemaskini'
	];
}
