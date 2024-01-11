<?php

namespace sisVentas\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Entity;
use sisVentas\Http\Requests\UsuarioFormRequest;
use sisVentas\Http\Requests\UsuarioFormUpdateRequest;
use sisVentas\User;

class UsuarioController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	public function index(Request $request) {
		if ($request) {
			$text = trim($request->get('searchText'));
			$usuarios = DB::table('users')
				->join('entities', 'users.entity_id', '=', 'entities.id')
				->orderBy('users.id', 'desc')
				->select(['users.id as user_id', 'users.email as user_name', 'entities.name as entity_name', 'entities.paternal_surname as entity_paternal_surname', 'entities.maternal_surname as entity_maternal_surname']);

				if ($text) {
					$usuarios = $usuarios->where(function($query) use($text){
						$query->where('users.email', 'LIKE', '%' . $text . '%')
							->orWhere('entities.name', 'LIKE', '%' . $text . '%')
							->orWhere('entities.paternal_surname', 'LIKE', '%' . $text . '%')
							->orWhere('entities.maternal_surname', 'LIKE', '%' . $text . '%');

					});
				}

				$usuarios = $usuarios->paginate(7);
			return view('seguridad.usuario.index', ["usuarios" => $usuarios, "searchText" => $text]);
		}
	}

	public function create() {
		$entities = Entity::whereType(2)
			->get();

		return view("seguridad.usuario.create", compact('entities'));
	}
	public function store(UsuarioFormRequest $request) {
		$usuario = new User;

		$data = $request->except('password');
		$data['email'] = $data['username'];
		$usuario->name = "";
		$usuario->fill($data);
		$usuario->password = bcrypt($request->get('password'));
		$usuario->save();
		return Redirect::to('seguridad/usuario');
	}
	public function edit($id) {

		$entities = Entity::whereType(2)
			->get();

		return view("seguridad.usuario.edit", ["usuario" => User::findOrFail($id), "entities" => $entities]);
	}
	public function update(UsuarioFormUpdateRequest $request, $id) {

		$usuario = User::findOrFail($id);
		$usuario->email = $request->get('username');
		$usuario->role_id = $request->get('role_id');

		if ($request->password) {
			$usuario->password = bcrypt($request->get('password'));
		}

		$usuario->update();
		return Redirect::to('seguridad/usuario');
	}
	public function destroy($id) {
		$usuario = DB::table('users')->where('id', '=', $id)->delete();
		return Redirect::to('seguridad/usuario');
	}
}
