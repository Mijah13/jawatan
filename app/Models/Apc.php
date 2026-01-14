<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Apc
 * 
 * @property int $id
 * @property Carbon $tahunterima
 * @property Carbon $tarikhkemaskini
 * @property int $id_kakitangan
 *
 * @package App\Models
 */
class Apc extends Model
{
	protected $table = 'apc';
	public $timestamps = false;

	protected $casts = [
		'tarikhkemaskini' => 'datetime',
		'id_kakitangan' => 'int'
	];

	protected $fillable = [
		'tahunterima',
		'tarikhkemaskini',
		'id_kakitangan'
	];
}
