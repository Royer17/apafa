<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
	use SoftDeletes;

	protected $table = 'orders';
	protected $dates = ['deleted_at'];

	protected $fillable = [
		'document_type_id',
		'number',
		'folios',
		'subject',
		'notes',
	];

	public function document_type() {
		return $this->belongsTo('sisVentas\DocumentType', 'document_type_id');
	}

	public function office() {
		return $this->belongsTo('sisVentas\Office', 'office_id');
	}

	public function entity() {
		return $this->belongsTo('sisVentas\Entity', 'entity_id');
	}

	public function details() {
		return $this->hasMany('sisVentas\DetailOrder', 'order_id');
	}

	public function detail() {
		return $this->hasOne('sisVentas\DetailOrder', 'order_id');
	}



}
