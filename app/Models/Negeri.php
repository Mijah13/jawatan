<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Negeri
 * 
 * @property int $id
 * @property string $nama
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class Negeri extends Model
{
	protected $table = 'negeri';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'nama',
		'kemaskini'
	];
}
