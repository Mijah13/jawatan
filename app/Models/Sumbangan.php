<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sumbangan
 * 
 * @property int $id
 * @property string|null $mykad
 * @property string $sumbangan
 * @property int $peringkat
 * @property Carbon $tarikh
 * @property Carbon $tarikhkemaskini
 * @property int $id_kakitangan
 *
 * @package App\Models
 */
class Sumbangan extends Model
{
	protected $table = 'sumbangan';
	public $timestamps = false;

	protected $casts = [
		'peringkat' => 'int',
		'tarikh' => 'datetime',
		'tarikhkemaskini' => 'datetime',
		'id_kakitangan' => 'int'
	];

	protected $fillable = [
		'mykad',
		'sumbangan',
		'peringkat',
		'tarikh',
		'tarikhkemaskini',
		'id_kakitangan'
	];
}
