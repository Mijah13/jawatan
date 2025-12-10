<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Perjawatanprogram
 * 
 * @property int $id
 * @property int $idjawatan
 * @property int $idgred
 * @property int $idprogram
 * @property int $bilperjawatan
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Perjawatanprogram extends Model
{
	protected $table = 'perjawatanprogram';
	public $timestamps = false;

	protected $casts = [
		'idjawatan' => 'int',
		'idgred' => 'int',
		'idprogram' => 'int',
		'bilperjawatan' => 'int',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'idjawatan',
		'idgred',
		'idprogram',
		'bilperjawatan',
		'kemaskini'
	];
}
