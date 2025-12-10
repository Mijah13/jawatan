<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Penempatan
 * 
 * @property int $id
 * @property string $kod
 * @property string $jenis
 *
 * @package App\Models
 */
class Penempatan extends Model
{
	protected $table = 'penempatan';
	public $timestamps = false;

	protected $fillable = [
		'kod',
		'jenis'
	];
}
