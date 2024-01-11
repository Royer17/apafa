<?php

namespace sisVentas\Http\Controllers\Landing;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Mail;
use sisVentas\Categoria;
use sisVentas\Company;
use sisVentas\DetailOrder;
use sisVentas\Entity;
use sisVentas\Http\Requests\LoggedSolicitudeRequest;
use sisVentas\Http\Requests\OrderRequest;
use sisVentas\Order;
use sisVentas\Venta;
use Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller {

	public function store(OrderRequest $request) {

		$data = $request->all();

		if ($data['type_document'] == 2) {
			$data['name'] = $data['business_name'];
			$data['identity_document'] = $data['ruc'];

		}

		$entity = Entity::whereIdentityDocument($data['identity_document'])->first();

		if (!$entity) {
			$entity = new Entity();
			$entity->code = "-";
			$entity->profession_id = 0;
			$entity->type = 1;
			$entity->office_id = 0;
			$entity->status = 1;
		}

		$entity->fill($data);
		$entity->save();

		$new_order = new Order();
		$new_order->fill($data);

		$new_order->entity_id = $entity->id;
		$new_order->office_id = 1;
		$new_order->status = 1;
		$new_order->year = Carbon::now()->format('Y');

		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');

		    Storage::disk('google')->put($file->getClientOriginalName(), fopen($file, 'r+'));
            $url = Storage::disk('google')->url($file->getClientOriginalName());
			$new_order->attached_file = $url;
			//$file->move(public_path() . '/archivos/tramites/', $file->getClientOriginalName());
			//$path = '/archivos/tramites/' . $file->getClientOriginalName();
			//$new_order->attached_file = $path;
		}

		$new_order->save();

		$number_of_characters = strlen($new_order->id);
		$total_length = 5;

		if ($number_of_characters >= 5) {
			$code = $new_order->id;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$new_order->id}";

		}


		$new_order->code = $code;
		$new_order->save();

		$new_detail = new DetailOrder();
		$new_detail->order_id = $new_order->id;
		$new_detail->status = 1;
		$new_detail->office_id = 1;
		$new_detail->observations = "NINGUNO";
		$new_detail->save();

		// //Send email
		// $company = $this->companyRepository->firstCompany();
		// $data['logo'] = $company->logotype_image;
		// $data['message'] = $data['message'];
		// $data['date'] = Date::parse($newInscription->created_at)->format('d-F-Y');
		// $data['companyName'] = $company->company_name;
		// $data['firstname'] = $data['fullname'];

		// if ($data['course'] != 0) {
		// 	$cycle = $this->cycleRepository->find($data['course']);
		// 	$data['course'] = $cycle->name_without_html;
		// } else {
		// 	$data['course'] = "No ha Seleccionado un curso";
		// }
		// //return $data;
		// Mail::to($data['email'])->send(new PreRegistration($data, $company->email));
		$company = Company::first();

		$logo = $company->logo;
		$company_name = $company->name;
		$firstname = "Luis";
		$dni_ruc = "7214334";
		$course = "dwada";
		$date = "19/09/2020";
		$city = "Tacna";
		$email = "my@gmail.com";
		$phone = "993943";
		$payment_way_id = "1";
		$amount = "10";
		$account_name = "mi cuenta";

		// Mail::send('emails.notification_entity', ['logo' => $logo, 'company_name' => $company_name, 'firstname' => $firstname, 'dni_ruc' => $dni_ruc, 'course' => $course, 'date' => $date, 'city' => $city, 'email' => $email, 'phone' => $phone, 'phone', 'payment_way_id' => $payment_way_id, 'amount' => $amount, 'account_name' => $amount], function ($m) use ($entity, $company) {
		// 	$m->from($company->email, $company->name);

		// 	$m->to($entity->email, $entity->name . " " . $entity->paternal_surname . " " . $entity->maternal_surname)->subject('Solicitud registrada');
		// });


		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente su solicitud', 'id' => $new_order->id], 201);
	}

	public function store_logged_solicitude(LoggedSolicitudeRequest $request)
	{
		$data = $request->all();
		$new_order = new Order();
		$new_order->fill($data);

		$new_order->entity_id = Auth::user()->entity_id;
		$new_order->office_id = $data['office_id'];
		$new_order->status = 1;
		$new_order->year = Carbon::now()->format('Y');

		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');
			$file->move(public_path() . '/archivos/tramites/', $file->getClientOriginalName());
			$path = '/archivos/tramites/' . $file->getClientOriginalName();
			$new_order->attached_file = $path;
		}

		$new_order->save();

		$number_of_characters = strlen($new_order->id);
		$total_length = 5;

		if ($number_of_characters >= 5) {
			$code = $new_order->id;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$new_order->id}";

		}

		$new_order->code = $code;
		$new_order->save();

		$new_detail = new DetailOrder();
		$new_detail->order_id = $new_order->id;
		$new_detail->status = 1;
		$new_detail->office_id = $data['office_id'];
		$new_detail->observations = "NINGUNO";
		$new_detail->save();
		
		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha registrado correctamente su solicitud'], 201);
	}


	public function view_order($id) {
		$order = Venta::with('products')
			->with('persona')
			->find($id);

		$company = Company::first();

		return view('store.order.detail_view', compact('order', 'company'));
	}

	public function confirm($id) {
		$order = Venta::with('products')
			->with('persona')
			->find($id);

		foreach ($order->products as $key => $product) {
			$product->stock = $product->stock - $product->pivot->cantidad;
			$product->save();
		}

		$order->estado = "2";
		$order->save();
		return;
	}

	public function search(Request $request) {

        try {
            //$llaveSecreta = "6Lf4sWMaAAAAAOO_17d8yKno6LFrD4m_jLRWds2u";
            //publickey 6Lf4sWMaAAAAACucNiVRqmvIJfMKmINEru9glEVX
            $llaveSecreta = "6Lc_924aAAAAAEQzt05tSqnzGWnGGNv44rF9Uv46";
            $ip = $_SERVER['REMOTE_ADDR'];
            $captcha = $request->input('recaptcha');
            $respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$llaveSecreta&response=$captcha&remoteip=$ip");
            $aux = json_decode($respuesta,true);

            if ($aux['success'] == true)
            {
				$identity_document = $request->identity_document;
				$year = $request->year;

				$orders = DB::table('orders')
					//->join('entities', 'orders.entity_id', '=', 'entities.id')
					// ->where(function ($query) use($identity_document) {
		   //             $query->where('orders.code', $identity_document)
		   //                   ->orWhere('entities.identity_document', $identity_document);
		   //         	})
		           	->where('orders.code', $identity_document)
		           	->where('orders.year', $year)
		           	->select(['orders.id as id'])
		           	->where('orders.deleted_at', null)
		           	//->where('entities.deleted_at', null)
		           	->get();
				// $orders = DB::table('orders')
				// 	->join('entities', 'orders.entity_id', '=', 'entities.id')
				// 	->where(function ($query) use($identity_document) {
		  //              $query->where('orders.code', $identity_document)
		  //                    ->orWhere('entities.identity_document', $identity_document);
		  //          	})
		  //          	->select(['orders.id as id'])
		  //          	->where('orders.deleted_at', null)
		  //          	->where('entities.deleted_at', null)
		  //          	->get();

				if (empty($orders)) {
					return response()->json(['title' => 'Aviso', 'message' => 'No se ha encontrado el documento'], 400);
				}

				$ids = [];

				foreach ($orders as $key => $order) {
					$ids[] = $order->id;
				}

				return response()->json(['ids' => $ids], 200);
            }
            else{
            	return response()->json(['title' => 'Error', 'message' => 'Actualice la página. El reCAPTCHA ya fue utilizado.'], 400);
            }

        } catch (Exception $e) {
            return response()->json(['title' => 'Error', 'message' => 'Error procesando el captcha'], 400);
        }
	}

	public function details_document_view(Request $request) {
		$products = [];
		$total = 0;

		$order_id = $request->order_id;
		$ids = explode(',', $order_id);
		$orders = Order::whereIn('id', $ids)
			->with('document_type')
			->with('entity')
			->with('office')
			->with(['detail' => function ($query) {
				$query->with('state')
					->with('office.entity')
					->orderBy('id', 'DESC');
			}])
			->orderBy('created_at', 'DESC')
			->get();

		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();


		$search_button = true;
		return view('store.checkout.shopping_cart', compact('products', 'total', 'categories', 'orders', 'search_button'));
	}

	public function request_completed(Request $request) {

		$order_id = $request->order_id;

		$order = Order::select(['id', 'code'])
			->find($order_id);
		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();
		$search_button = true;

		return view('store.completed', compact('categories', 'order', 'search_button'));
	}
}
