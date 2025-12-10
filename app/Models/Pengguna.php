<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pengguna
 * 
 * @property int $id
 * @property string $nama
 * @property int $level
 * @property string $email
 * @property string $catatan
 * @property string $kata_laluan
 *
 * @package App\Models
 */
class Pengguna extends Model
{
	protected $table = 'pengguna';
	public $timestamps = false;

	protected $casts = [
		'level' => 'int'
	];

	protected $fillable = [
		'nama',
		'level',
		'email',
		'catatan',
		'kata_laluan'
	];
}
