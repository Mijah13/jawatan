<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pencapaian
 * 
 * @property int $id
 * @property string|null $mykad
 * @property string $pencapaian
 * @property int $peringkat
 * @property Carbon $tarikhpencapaian
 * @property Carbon $tarikhkemaskini
 * @property int $id_kakitangan
 *
 * @package App\Models
 */
class Pencapaian extends Model
{
	protected $table = 'pencapaian';
	public $timestamps = false;

	protected $casts = [
		'peringkat' => 'int',
		'tarikhpencapaian' => 'datetime',
		'tarikhkemaskini' => 'datetime',
		'id_kakitangan' => 'int'
	];

	protected $fillable = [
		'mykad',
		'pencapaian',
		'peringkat',
		'tarikhpencapaian',
		'tarikhkemaskini',
		'id_kakitangan'
	];
}
