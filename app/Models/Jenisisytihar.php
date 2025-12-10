<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Jenisisytihar
 * 
 * @property int $id
 * @property string $jenis
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Jenisisytihar extends Model
{
	protected $table = 'jenisisytihar';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'jenis',
		'kemaskini'
	];
}
