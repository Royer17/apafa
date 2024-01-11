<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model {
	protected $table = 'entities';

	protected $fillable = [
		'code',
		'name',
		'paternal_surname',
		'maternal_surname',
		'identity_document',
		'ruc',
		'profession_id',
		'address',
		'cellphone',
		'email',
		'office_id',
		'status',
		'type_document',
	];

	public function office()
	{
		return $this->belongsTo('sisVentas\Office', 'office_id');
	}

}
