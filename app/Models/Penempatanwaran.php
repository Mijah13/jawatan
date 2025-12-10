<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Penempatanwaran
 * 
 * @property int $id
 * @property string $mykad
 * @property int $penempatanwaran
 *
 * @package App\Models
 */
class Penempatanwaran extends Model
{
	protected $table = 'penempatanwaran';
	public $timestamps = false;

	protected $casts = [
		'penempatanwaran' => 'int'
	];

	protected $fillable = [
		'mykad',
		'penempatanwaran'
	];
}
