<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Elaun
 * 
 * @property int $id
 * @property string $nama
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Elaun extends Model
{
	protected $table = 'elaun';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'nama',
		'kemaskini'
	];
}
