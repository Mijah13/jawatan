<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Isyiharhartum
 * 
 * @property int $id
 * @property string|null $mykad
 * @property Carbon $tarikhisytihar
 * @property int $jenis
 * @property Carbon $tarikhkemaskini
 * @property int $id_kakitangan
 * @property string|null $no_rujukan
 *
 * @package App\Models
 */
class Isyiharhartum extends Model
{
	protected $table = 'isyiharharta';
	public $timestamps = false;

	protected $casts = [
		'tarikhisytihar' => 'datetime',
		'jenis' => 'int',
		'tarikhkemaskini' => 'datetime',
		'id_kakitangan' => 'int'
	];

	protected $fillable = [
		'mykad',
		'tarikhisytihar',
		'jenis',
		'tarikhkemaskini',
		'id_kakitangan',
		'no_rujukan'
	];
}
