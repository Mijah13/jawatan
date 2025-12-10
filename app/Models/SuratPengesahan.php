<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SuratPengesahan
 * 
 * @property int $id
 * @property int $idkakitangan
 * @property string $kepada
 * @property string $alamat1
 * @property string|null $alamat2
 * @property int $poskod
 * @property string $bandar
 * @property int $negeri
 * @property string|null $fail
 * @property int|null $status
 * @property Carbon|null $tarikh_sah
 * @property int|null $pengesah
 * @property Carbon $kemaskini
 * @property Carbon|null $tarikhmohon
 *
 * @package App\Models
 */
class SuratPengesahan extends Model
{
	protected $table = 'surat_pengesahan';
	public $timestamps = false;

	protected $casts = [
		'idkakitangan' => 'int',
		'poskod' => 'int',
		'negeri' => 'int',
		'status' => 'int',
		'tarikh_sah' => 'datetime',
		'pengesah' => 'int',
		'kemaskini' => 'datetime',
		'tarikhmohon' => 'datetime'
	];

	protected $fillable = [
		'idkakitangan',
		'kepada',
		'alamat1',
		'alamat2',
		'poskod',
		'bandar',
		'negeri',
		'fail',
		'status',
		'tarikh_sah',
		'pengesah',
		'kemaskini',
		'tarikhmohon'
	];
}
