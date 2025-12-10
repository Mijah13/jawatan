<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class KategoriLatihan
 * 
 * @property int $id
 * @property string $kategori
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class KategoriLatihan extends Model
{
	protected $table = 'kategori_latihan';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'kategori',
		'kemaskini'
	];
}
