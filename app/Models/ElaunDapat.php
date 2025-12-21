<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ElaunDapat
 * 
 * @property int $id
 * @property int $idkakitangan
 * @property int $elaun
 * @property float $nilai
 *
 * @package App\Models
 */
class ElaunDapat extends Model
{
	protected $table = 'elaun_dapat';
	public $timestamps = false;

	protected $casts = [
		'idkakitangan' => 'int',
		'elaun' => 'int',
		'nilai' => 'float'
	];

	protected $fillable = [
		'idkakitangan',
		'elaun',
		'nilai'
	];

	public function elaunRelation()
	{
		return $this->belongsTo(Elaun::class, 'elaun', 'id');
	}
}
