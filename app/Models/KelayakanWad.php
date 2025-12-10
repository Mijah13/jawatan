<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class KelayakanWad
 * 
 * @property int $id
 * @property string $gred
 * @property string $kelayakan
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class KelayakanWad extends Model
{
	protected $table = 'kelayakan_wad';
	public $timestamps = false;

	protected $casts = [
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'gred',
		'kelayakan',
		'kemaskini'
	];
}
