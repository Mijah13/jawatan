<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Levelpengguna
 * 
 * @property int $id
 * @property int $level
 * @property string $nama
 *
 * @package App\Models
 */
class Levelpengguna extends Model
{
	protected $table = 'levelpengguna';
	public $timestamps = false;

	protected $casts = [
		'level' => 'int'
	];

	protected $fillable = [
		'level',
		'nama'
	];
}
