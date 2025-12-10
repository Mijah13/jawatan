<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Unit
 * 
 * @property int $id
 * @property int $program
 * @property string $unit
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Unit extends Model
{
	protected $table = 'unit';
	public $timestamps = false;

	protected $casts = [
		'program' => 'int',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'program',
		'unit',
		'kemaskini'
	];
}
