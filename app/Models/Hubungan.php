<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Hubungan
 * 
 * @property int $id
 * @property string $hubungan
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Hubungan extends Model
{
	protected $table = 'hubungan';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'hubungan',
		'kemaskini'
	];
}
