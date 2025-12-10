<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Gred
 * 
 * @property int $id
 * @property string $gred
 * @property int|null $keutamaan
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Gred extends Model
{
	protected $table = 'gred';
	public $timestamps = false;

	protected $casts = [
		'keutamaan' => 'int',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'gred',
		'keutamaan',
		'kemaskini'
	];
}
