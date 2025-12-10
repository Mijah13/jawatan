<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Peringkatsumbangan
 * 
 * @property int $id
 * @property string $peringkat
 *
 * @package App\Models
 */
class Peringkatsumbangan extends Model
{
	protected $table = 'peringkatsumbangan';
	public $timestamps = false;

	protected $fillable = [
		'peringkat'
	];
}
