<?php

namespace sisVentas;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model {
	protected $table = 'document_types';

	public $timestamps = false;

	protected $fillable = [
		'code',
		'name',
		'sigla',
		'status',
	];

}
