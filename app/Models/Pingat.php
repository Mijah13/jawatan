<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pingat
 * 
 * @property int $id
 * @property string $mykad
 * @property string|null $pingat
 * @property Carbon|null $kemaskini
 * @property Carbon|null $tarikhterima
 * @property int $id_kakitangan
 *
 * @package App\Models
 */
class Pingat extends Model
{
	protected $table = 'pingat';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime',
		'tarikhterima' => 'datetime',
		'id_kakitangan' => 'int'
	];

	protected $fillable = [
		'mykad',
		'pingat',
		'kemaskini',
		'tarikhterima',
		'id_kakitangan'
	];
}
