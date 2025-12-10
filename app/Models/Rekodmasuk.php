<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rekodmasuk
 * 
 * @property int $id
 * @property string $user
 * @property Carbon $tarikhmasuk
 *
 * @package App\Models
 */
class Rekodmasuk extends Model
{
	protected $table = 'rekodmasuk';
	public $timestamps = false;

	protected $casts = [
		'tarikhmasuk' => 'datetime'
	];

	protected $fillable = [
		'user',
		'tarikhmasuk'
	];
}
