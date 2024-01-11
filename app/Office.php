<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class Office extends Model {
	protected $table = 'offices';

	protected $fillable = [
		'name',
		'code',
		'sigla',
		'entity_id',
		'upper_office_id',
	];

	public function entity() {
		return $this->belongsTo('sisVentas\Entity', 'entity_id');
	}

}
