<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Jawatan
 * 
 * @property int $id
 * @property string $kod
 * @property string $jawatan
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Jawatan extends Model
{
	protected $table = 'jawatan';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'kod',
		'jawatan',
		'kemaskini'
	];
}
