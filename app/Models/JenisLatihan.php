<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JenisLatihan
 * 
 * @property int $id
 * @property string $jenis
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class JenisLatihan extends Model
{
	protected $table = 'jenis_latihan';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'jenis',
		'kemaskini'
	];
}
