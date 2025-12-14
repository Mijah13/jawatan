<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Perjawatan
 * 
 * @property int $id
 * @property int $jawatan
 * @property int $gred
 * @property int $bilanganperjawatan
 * @property Carbon $tarikhkemaskini
 * @property int|null $penempatan
 * @property string|null $waran
 *
 * @package App\Models
 */
class Perjawatan extends Model
{
	protected $table = 'perjawatan';
	public $timestamps = false;

	protected $casts = [
		'jawatan' => 'int',
		'gred' => 'int',
		'bilanganperjawatan' => 'int',
		'tarikhkemaskini' => 'datetime',
		'penempatan' => 'int'
	];

	protected $fillable = [
		'jawatan',
		'gred',
		'bilanganperjawatan',
		'tarikhkemaskini',
		'penempatan',
		'waran',
		'program',
		'unit'
	];

	// Relationships
	public function jawatanRel()
	{
		return $this->belongsTo(Jawatan::class, 'jawatan', 'id');
	}

	public function gredRel()
	{
		return $this->belongsTo(Gred::class, 'gred', 'id');
	}

	public function organisasiRel()
	{
		return $this->belongsTo(Organisasi::class, 'program', 'id');
	}

	public function unitRel()
	{
		return $this->belongsTo(Unit::class, 'unit', 'id');
	}
}
