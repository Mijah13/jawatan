<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Test
 * 
 * @property int $id
 * @property string|null $nama
 * @property Carbon|null $tarikh
 * @property Carbon|null $tarikh2
 * @property string|null $tarikh3
 *
 * @package App\Models
 */
class Test extends Model
{
	protected $table = 'test';
	public $timestamps = false;

	protected $casts = [
		'tarikh' => 'datetime',
		'tarikh2' => 'datetime'
	];

	protected $fillable = [
		'nama',
		'tarikh',
		'tarikh2',
		'tarikh3'
	];
}
