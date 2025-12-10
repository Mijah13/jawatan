<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SuratPengesahanPelulu
 * 
 * @property int $id
 * @property int $idpelulus
 * @property int $pengguna
 * @property Carbon|null $tarikh
 * @property Carbon $kemaskini
 *
 * @package App\Models
 */
class SuratPengesahanPelulu extends Model
{
	protected $table = 'surat_pengesahan_pelulus';
	public $timestamps = false;

	protected $casts = [
		'idpelulus' => 'int',
		'pengguna' => 'int',
		'tarikh' => 'datetime',
		'kemaskini' => 'datetime'
	];

	protected $fillable = [
		'idpelulus',
		'pengguna',
		'tarikh',
		'kemaskini'
	];
}
