<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	protected $table = 'kakitangan';
	public $timestamps = false;

	protected $fillable = [
		'mykad',
		'katalaluan',
		'aktif',
		'level',
		'nama',
		'emel',
	];

	protected $hidden = [
		'katalaluan',
	];

	// Laravel uses this for password checking
	public function getAuthPassword()
	{
		return $this->katalaluan;
	}
}
