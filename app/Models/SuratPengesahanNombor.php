<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SuratPengesahanNombor
 * 
 * @property int $id
 * @property string $running
 *
 * @package App\Models
 */
class SuratPengesahanNombor extends Model
{
	protected $table = 'surat_pengesahan_nombor';
	public $timestamps = false;

	protected $fillable = [
		'running'
	];
}
