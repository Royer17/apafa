<?php

namespace sisVentas\Http\Controllers;

use DB;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Entity;
use sisVentas\Http\Requests\OfficeFormRequest;
use sisVentas\Office;

class OfficeController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$offices = DB::table('offices')->where('name', 'LIKE', '%' . $query . '%')
				->orderBy('id', 'desc')
				->paginate(7);
			return view('almacen.office.index', ["offices" => $offices, "searchText" => $query]);
		}
	}
	public function create() {

		$entities = Entity::whereType(2)
			->get();

		$offices = Office::all();
		return view("almacen.office.create", compact('entities', 'offices'));
	}
	public function store(OfficeFormRequest $request) {
		$data = $request->all();

		$office = new Office;
		$office->fill($data);
		$office->save();
		return Redirect::to('admin/oficinas');
	}

	// public function show($id) {
	// 	return view("almacen.categoria.show", ["categoria" => Categoria::findOrFail($id)]);
	// }

	public function edit($id) {

		$entities = Entity::whereType(2)
			->get();

		$offices = Office::all();
		return view("almacen.office.edit", ["office" => Office::findOrFail($id), "entities" => $entities, "offices" => $offices]);
	}

	public function update(OfficeFormRequest $request, $id) {
		$data = $request->all();

		$office = Office::findOrFail($id);
		$office->fill($data);
		$office->save();
		return Redirect::to('admin/oficinas');
	}

	public function destroy($id) {
		$office = Office::findOrFail($id);
		$office->delete();
		return Redirect::to('admin/oficinas');
	}

	public function reporte() {
		//Obtenemos los registros
		$registros = DB::table('categoria')
			->where('condicion', '=', '1')
			->orderBy('nombre', 'asc')
			->get();

		$pdf = new Fpdf();
		$pdf::AddPage();
		$pdf::SetTextColor(35, 56, 113);
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Cell(0, 10, utf8_decode("Listado Categorías"), 0, "", "C");
		$pdf::Ln();
		$pdf::Ln();
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda
		$pdf::SetFont('Arial', 'B', 10);
		//El ancho de las columnas debe de sumar promedio 190
		$pdf::cell(50, 8, utf8_decode("Nombre"), 1, "", "L", true);
		$pdf::cell(140, 8, utf8_decode("Descripción"), 1, "", "L", true);

		$pdf::Ln();
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
		$pdf::SetFont("Arial", "", 9);

		foreach ($registros as $reg) {
			$pdf::cell(50, 6, utf8_decode($reg->nombre), 1, "", "L", true);
			$pdf::cell(140, 6, utf8_decode($reg->descripcion), 1, "", "L", true);
			$pdf::Ln();
		}

		$pdf::Output();
		exit;
	}

}
