<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model {
	protected $table = 'details_order';

	protected $fillable = [

	];

	public function office() {
		return $this->belongsTo('sisVentas\Office', 'office_id');
	}

	public function state() {
		return $this->belongsTo('sisVentas\DocumentState', 'status');
	}

}
