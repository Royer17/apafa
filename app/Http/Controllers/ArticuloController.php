<?php

namespace sisVentas\Http\Controllers;

use DB;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Articulo;
use sisVentas\Categoria;
use sisVentas\Http\Requests\ArticuloFormRequest;

class ArticuloController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$articulos = DB::table('articulo as a')
				->join('categoria as c', 'a.idcategoria', '=', 'c.idcategoria')
				->select('a.idarticulo', 'a.nombre', 'a.codigo', 'a.stock', 'c.nombre as categoria', 'a.descripcion', 'a.imagen', 'a.estado')
				->where('a.nombre', 'LIKE', '%' . $query . '%')
				->orwhere('a.codigo', 'LIKE', '%' . $query . '%')
				->orderBy('a.idarticulo', 'desc')
				->paginate(7);
			return view('almacen.articulo.index', ["articulos" => $articulos, "searchText" => $query]);
		}
	}
	public function create() {
		$categorias = DB::table('categoria')->where('condicion', '=', '1')->get();
		return view("almacen.articulo.create", ["categorias" => $categorias]);
	}
	public function store(ArticuloFormRequest $request) {
		$articulo = new Articulo;
		$articulo->idcategoria = $request->get('idcategoria');
		$articulo->codigo = $request->get('codigo');
		$articulo->nombre = $request->get('nombre');
		$articulo->slug = str_slug($request->get('nombre'));
		$articulo->price = $request->get('price');
		$articulo->stock = $request->get('stock');
		$articulo->descripcion = $request->get('descripcion');
		$articulo->estado = 'Activo';

		if (Input::hasFile('imagen')) {
			$file = Input::file('imagen');
			$file->move(public_path() . '/imagenes/articulos/', $file->getClientOriginalName());
			$path = '/imagenes/articulos/' . $file->getClientOriginalName();
			$articulo->imagen = $path;
		}
		$articulo->save();
		return Redirect::to('almacen/articulo');

	}
	public function show($id) {
		return view("almacen.articulo.show", ["articulo" => Articulo::findOrFail($id)]);
	}
	public function edit($id) {
		$articulo = Articulo::findOrFail($id);
		$categorias = DB::table('categoria')->where('condicion', '=', '1')->get();
		return view("almacen.articulo.edit", ["articulo" => $articulo, "categorias" => $categorias]);
	}

	public function update(ArticuloFormRequest $request, $id) {
		$articulo = Articulo::findOrFail($id);

		$articulo->idcategoria = $request->get('idcategoria');
		$articulo->codigo = $request->get('codigo');
		$articulo->nombre = $request->get('nombre');
		$articulo->slug = str_slug($request->get('nombre'));
		$articulo->price = $request->get('price');
		$articulo->stock = $request->get('stock');
		$articulo->descripcion = $request->get('descripcion');
		$articulo->estado = 'Activo';

		if (Input::hasFile('imagen')) {
			$file = Input::file('imagen');
			$file->move(public_path() . '/imagenes/articulos/', $file->getClientOriginalName());
			$path = '/imagenes/articulos/' . $file->getClientOriginalName();
			$articulo->imagen = $path;
		}

		$articulo->update();
		return Redirect::to('almacen/articulo');
	}
	public function destroy($id) {
		$articulo = Articulo::findOrFail($id);
		$articulo->Estado = 'Inactivo';
		$articulo->update();
		return Redirect::to('almacen/articulo');
	}
	public function reporte() {
		//Obtenemos los registros
		$registros = DB::table('articulo as a')
			->join('categoria as c', 'a.idcategoria', '=', 'c.idcategoria')
			->select('a.idarticulo', 'a.nombre', 'a.codigo', 'a.stock', 'c.nombre as categoria', 'a.descripcion', 'a.imagen', 'a.estado')
			->orderBy('a.nombre', 'asc')
			->get();

		$pdf = new Fpdf();
		$pdf::AddPage();
		$pdf::SetTextColor(35, 56, 113);
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Cell(0, 10, utf8_decode("Listado Artículos"), 0, "", "C");
		$pdf::Ln();
		$pdf::Ln();
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda
		$pdf::SetFont('Arial', 'B', 10);
		//El ancho de las columnas debe de sumar promedio 190
		$pdf::cell(30, 8, utf8_decode("Código"), 1, "", "L", true);
		$pdf::cell(80, 8, utf8_decode("Nombre"), 1, "", "L", true);
		$pdf::cell(65, 8, utf8_decode("Categoría"), 1, "", "L", true);
		$pdf::cell(15, 8, utf8_decode("Stock"), 1, "", "L", true);

		$pdf::Ln();
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
		$pdf::SetFont("Arial", "", 9);

		foreach ($registros as $reg) {
			$pdf::cell(30, 6, utf8_decode($reg->codigo), 1, "", "L", true);
			$pdf::cell(80, 6, utf8_decode($reg->nombre), 1, "", "L", true);
			$pdf::cell(65, 6, utf8_decode($reg->categoria), 1, "", "L", true);
			$pdf::cell(15, 6, utf8_decode($reg->stock), 1, "", "L", true);
			$pdf::Ln();
		}

		$pdf::Output();
		exit;
	}

	public function import(Request $request) {

		$categories = $request->category;

		$codes = $request->code;
		$names = $request->name;
		$stocks = $request->stock;
		$descriptions = $request->description;
		$status = "Activo";
		$prices = $request->price;
		foreach ($codes as $key => $code) {

			$product = Articulo::whereCodigo($code)
				->first();

			$category_id = $this->get_category_id_by_name($categories[$key]);

			if ($product) {
				$product->stock = $product->stock + $stocks[$key];
			} else {
				$product = new Articulo();
				$product->codigo = $code;
				$product->stock = $stocks[$key];

			}

			$product->idcategoria = $category_id;
			$product->nombre = $names[$key];
			$product->descripcion = $descriptions[$key];
			$product->estado = $status;
			$product->slug = str_slug($names[$key]);
			$product->price = $prices[$key];
			$product->save();
		}

	}

	public function get_category_id_by_name($name) {

		$category = Categoria::whereSlug(str_slug($name))
			->first();

		if ($category) {
			return $category->idcategoria;
		}
		#create;

		$new_category = new Categoria();
		$new_category->nombre = $name;
		$new_category->descripcion = "";
		$new_category->condicion = 1;
		$new_category->slug = str_slug($name);
		$new_category->save();

		return $new_category->idcategoria;

	}

}
