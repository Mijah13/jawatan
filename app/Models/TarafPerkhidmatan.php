<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TarafPerkhidmatan
 * 
 * @property int $id
 * @property string $taraf
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class TarafPerkhidmatan extends Model
{
	protected $table = 'taraf_perkhidmatan';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'taraf',
		'kemaskini'
	];
}
