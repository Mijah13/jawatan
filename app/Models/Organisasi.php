<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Organisasi
 * 
 * @property int $id
 * @property string $program
 * @property string $kod
 * @property int|null $keutamaan
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Organisasi extends Model
{
	protected $table = 'organisasi';
	public $timestamps = false;

	protected $casts = [
		'keutamaan' => 'int',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'program',
		'kod',
		'keutamaan',
		'kemaskini'
	];
}
