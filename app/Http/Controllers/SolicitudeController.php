<?php

namespace sisVentas\Http\Controllers;

use Auth;
use DB;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Categoria;
use sisVentas\Company;
use sisVentas\DetailOrder;
use sisVentas\DocumentState;
use sisVentas\DocumentType;
use sisVentas\Entity;
use sisVentas\Http\Requests\OfficeFormRequest;
use sisVentas\Office;
use sisVentas\Order;
use sisVentas\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class SolicitudeController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index(Request $request) {
		if ($request) {

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);


			$text = trim($request->get('searchText'));
			$document_status = $request->document_status;
			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->join('offices', 'orders.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->where('orders.deleted_at', null);

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', $text)
                    		->orWhere('entities.identity_document', $text);
           			});
				}

				if ($document_status) {
					$orders = $orders->where('orders.status', $document_status);
				}


			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file']);
				// ->paginate(7);

			$offices = Office::all();

			$document_statuses = DocumentState::all();

			$flag = false;

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.index', ["orders" => $orders->paginate(7), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'flag' => $flag]);

			}

			if ($entity->office_id == 1) {
				#no admin
				// $office_id = $user->entity->office_id;
				// $orders = $orders->where('orders.office_id', $office_id);
				return view('almacen.solicitude.index', ["orders" => $orders->paginate(7), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'flag' => true]);
			}

			$office_id = $user->entity->office_id;
			$orders = $orders->where('orders.office_id', $office_id);

			return view('almacen.solicitude.index', ["orders" => $orders->paginate(7), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'flag' => $flag]);
		}
	}

	public function create_solicitude()
	{	
		$document_types = DocumentType::all();
		$user_id = Auth::user()->id;
		$user = User::with('entity.office.entity')->find($user_id);
		$offices = Office::all();
		return view('create_solicitude', compact('document_types', 'user', 'offices'));
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

	public function delete_solicitude($id) {
		$order = Order::find($id);

		$val = explode('id=', $order->attached_file);
		if (count($val) == 2) {
			$val = $val[1];
			$val = explode('&', $val); 
			$val = $val[0];
			Storage::disk('google')->delete($val);
		}
		$order->delete();
		return;
	}

	public function update_status(Request $request) {

		$order = Order::find($request->order_id);

		if ($order->status == 3) {
			if ($order->office_id == $request->office_id) {
				return redirect()->back()->with('data', ["El documento con código ".$order->code." no puede derivarse a la misma oficina donde ya está"]);
			}
		}

		$order->status = $request->status;

		if ($request->status == 2) {
			$order->office_id = $request->office_id;
		}

		$order->save();

		$new_detail_order = new DetailOrder();
		$new_detail_order->order_id = $order->id;
		$new_detail_order->status = $request->status;
		$new_detail_order->office_id = $order->office_id;

		if ($request->status == 2) {
			$new_detail_order->office_id = $request->office_id;
		}

		$new_detail_order->observations = $request->observations;
		$new_detail_order->save();

		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');

		    Storage::disk('google')->put($file->getClientOriginalName(), fopen($file, 'r+'));
            $url = Storage::disk('google')->url($file->getClientOriginalName());
			$new_detail_order->attached_file = $url;
			$new_detail_order->save();
			//$file->move(public_path() . '/archivos/tramites/', $file->getClientOriginalName());
			//$path = '/archivos/tramites/' . $file->getClientOriginalName();
			//$new_order->attached_file = $path;
		}

		return Redirect::to('admin/solicitudes');
	}

	public function report(Request $request) {
		$order = Order::with('document_type')
			->with('entity')
			->with('office')
			->with(['details' => function ($query) {
				$query->with('state')
					->with('office');
			}])->find($request->solicitude_id);

		$company = Company::first();

		$pdf = new Fpdf();
		$pdf::AddPage();
		//$pdf::SetTextColor(35, 56, 113);
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto

		$pdf::SetFont('Arial', '', 8);
		$pdf::Write(5, utf8_decode($company->name));
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 14);
		$pdf::Cell(0, 10, utf8_decode("Hoja de Ruta"), 0, "", "C");
		$pdf::Ln();
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Código: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->code);
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Tipo de documento: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->document_type->name);
		// $pdf::Cell(0, 10, utf8_decode("Tipo de documento: " . $order->document_type->name), 0, "", "L");
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Emisor: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, "{$order->entity->name} {$order->entity->paternal_surname} {$order->entity->maternal_surname}");

		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Asunto: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->subject);

		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Fecha de registro: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->created_at->format('d/m/Y H:i'));

		$pdf::Ln();
		$pdf::Ln();

		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Oficina actual: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->office->name);

		$pdf::Ln();
		$pdf::Ln();

		//El ancho de las columnas debe de sumar promedio 190

		$pdf::SetFont('Arial', 'B', 11);
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda

		$pdf::cell(10, 8, utf8_decode("#"), 1, "", "L", true);
		$pdf::cell(57, 8, utf8_decode("Estado / Fecha"), 1, "", "L", true);
		$pdf::cell(54, 8, utf8_decode("Oficina"), 1, "", "L", true);
		$pdf::cell(67, 8, utf8_decode("Observación"), 1, "", "L", true);

		$pdf::Ln();
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
		$pdf::SetFont("Arial", "", 9);

		foreach ($order->details as $key => $detail) {
			$pdf::cell(10, 6, $key + 1, 1, "", "L", true);
			$pdf::cell(57, 6, utf8_decode($detail->state->name . " - " . $detail->created_at->format('d/m/Y')), 1, "", "L", true);
			$pdf::cell(54, 6, utf8_decode($detail->office->name), 1, "", "L", true);
			$pdf::cell(67, 6, utf8_decode($detail->observations), 1, "", "L", true);
			$pdf::Ln();
		}

		$pdf::Output();
		exit;

		// $pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		// $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda
		// $pdf::SetFont('Arial', 'B', 10);
		// //El ancho de las columnas debe de sumar promedio 190
		// $pdf::cell(50, 8, utf8_decode("Nombre"), 1, "", "L", true);
		// $pdf::cell(140, 8, utf8_decode("Descripción"), 1, "", "L", true);

		// $pdf::Ln();
		// $pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		// $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
		// $pdf::SetFont("Arial", "", 9);

		// foreach ($registros as $reg) {
		// 	$pdf::cell(50, 6, utf8_decode($reg->nombre), 1, "", "L", true);
		// 	$pdf::cell(140, 6, utf8_decode($reg->descripcion), 1, "", "L", true);
		// 	$pdf::Ln();
		// }
		$pdf::Output();
		exit;
	}

	public function details_document_view(Request $request)
	{
		$products = [];
		$total = 0;

		$orders = Order::where('id', $request->solicitude_id)
			->with('document_type')
			->with('entity')
			->with('office')
			->with(['details' => function ($query) {
				$query->with('state')
					->with('office.entity');
			}])
			->orderBy('created_at', 'DESC')
			->get();

		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		$search_button = false;
		return view('store.checkout.solicitude_detail', compact('products', 'total', 'categories', 'orders', 'search_button'));
	}

	public function my_solicitude_sent_view(Request $request)
	{

		if ($request) {

			$user = User::with('entity')->find(Auth::user()->id);
			$document_status = $request->document_status;

			$text = trim($request->get('searchText'));
			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->join('offices', 'orders.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->where('orders.entity_id', '=', Auth::user()->entity_id);

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', $text);
           			});
				}
				
				if ($document_status) {
					$orders = $orders->where('orders.status', $document_status);
				}

			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file'])
				->paginate(7);

			$offices = Office::all();
			$document_statuses = DocumentState::all();

			return view('almacen.solicitude.my_solicitude', ["orders" => $orders, "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status]);
		}
	}

}
