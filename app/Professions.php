<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model {
	protected $table = 'professions';

	protected $fillable = [
		'code',
		'name',
		'sigla',
	];

}
