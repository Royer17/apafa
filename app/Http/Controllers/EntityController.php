<?php

namespace sisVentas\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Entity;
use sisVentas\Office;
use sisVentas\Profession;

class EntityController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$entities = DB::table('entities')->where('entities.name', 'LIKE', '%' . $query . '%')
				->orderBy('entities.id', 'desc')
				->where('entities.type', 2)
				->leftJoin('professions', 'entities.profession_id', '=', 'professions.id')
				->select(['entities.id as id', 'entities.identity_document as identity_document', DB::raw('CONCAT(entities.name, " ", entities.paternal_surname, " ", entities.maternal_surname) AS full_name'), 'entities.cellphone as cellphone', 'professions.name as profession_name'])
				->paginate(7);
			return view('almacen.entity.index', ["entities" => $entities, "searchText" => $query]);
		}
	}
	public function create() {

		$professions = Profession::all();
		$offices = Office::all();
		return view("almacen.entity.create", compact('professions', 'offices'));

	}
	public function store(Request $request) {
		$data = $request->all();

		$entity = new Entity;
		$entity->fill($data);
		$entity->type = 2;
		$entity->status = 1;

		$entity->save();
		return Redirect::to('admin/personal');
	}

	// public function show($id) {
	// 	return view("almacen.categoria.show", ["categoria" => Categoria::findOrFail($id)]);
	// }

	public function edit($id) {

		$professions = Profession::all();
		$offices = Office::all();

		return view("almacen.entity.edit", ["entity" => Entity::findOrFail($id), "professions" => $professions, "offices" => $offices]);
	}

	public function update(Request $request, $id) {
		$data = $request->all();

		$entity = entity::findOrFail($id);
		$entity->fill($data);
		$entity->save();
		return Redirect::to('admin/personal');
	}

	public function destroy($id) {
		$entity = entity::findOrFail($id);
		$entity->delete();
		return Redirect::to('admin/personal');
	}
}
