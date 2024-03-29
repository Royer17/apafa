<?php

namespace sisVentas\Http\Controllers\Landing;

use Illuminate\Routing\Controller;
use sisVentas\Articulo;
use sisVentas\Categoria;
use sisVentas\Company;
use sisVentas\DocumentState;
use sisVentas\DocumentType;
use sisVentas\Entity;
use sisVentas\Order;
use DB;

class HomeController extends Controller {

	public function index() {
		/*$categories = Categoria::with(['products_actived' => function ($query) {
			 		$query->select(['idarticulo as id', 'nombre as name', 'slug', 'price', 'idcategoria', 'imagen']);
			 	}])
			 	->whereCondicion(1)
			 	->select(['idcategoria', 'nombre as name', 'slug'])
			 	->get();

		*/

		$categories_outstanding = Categoria::whereCondicion(1)
			->whereOutstanding(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		$categories = Categoria::whereCondicion(1)
			->whereOutstanding(0)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		$products = Articulo::whereEstado('Activo')
			->paginate(10);

		$last_products = Articulo::whereEstado('Activo')
			->orderBy('idarticulo', 'DESC')
			->take(4)
			->get();

		$carousel_quantity = ceil(count($last_products) / 2);

		$company = Company::first();

		##NEW---
		$document_types = DocumentType::all();

		$search_button = true;

		return view('store.products.index', compact('categories', 'products', 'last_products', 'carousel_quantity', 'company', 'categories_outstanding', 'document_types', 'search_button'));
	}

	public function view_products() {

		$categories_outstanding = Categoria::whereCondicion(1)
			->whereOutstanding(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		$categories = Categoria::whereCondicion(1)
			->whereOutstanding(0)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		$products = Articulo::whereEstado('Activo')
			->paginate(10);

		$last_products = Articulo::whereEstado('Activo')
			->orderBy('idarticulo', 'DESC')
			->take(4)
			->get();
		$carousel_quantity = ceil(count($last_products) / 2);

		return view('store.products.index', compact('categories', 'products', 'last_products', 'carousel_quantity', 'categories_outstanding'));
	}

	public function view_product_profile($slug) {

		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		$product = Articulo::with('category')
			->whereSlug($slug)
			->first();

		$products_related = Articulo::where('idcategoria', $product->idcategoria)
			->where('idarticulo', '!=', $product->idarticulo)
			->get();

		return view('store.products.profile.index', compact('product', 'categories', 'products_related'));
	}

	public function view_cart() {
		$products = [];
		$total = 0;

		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		return view('store.checkout.shopping_cart', compact('products', 'total', 'categories'));
	}

	public function view_order() {
		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();
		// $years = Order::select(['year'])
		// 	->groupBy('year')
		// 	->orderBy('year', 'DESC')
		// 	->get();

		$years = DB::table('orders')
			->groupBy('year')
			->orderBy('year', 'DESC')
			->select(['year'])
			->where('deleted_at', NULL)
			->get();

		$search_button = false;

		return view('store.checkout.check_out', compact('categories', 'years', 'search_button'));
	}

	public function view_about_us() {

		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		return view('store.about_us', compact('categories'));
	}

	public function details_document_view(Request $request) {
		$products = [];
		$total = 0;

		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		return view('store.checkout.shopping_cart', compact('products', 'total', 'categories'));
	}

	public function get_document_state($id) {

		$document_state = DocumentState::find($id);
		return $document_state;

	}

	public function email_view() {

		$company = Company::first();

		$logo = $company->logo;
		$company_name = $company->name;
		$firstname = "Luis";
		$dni_ruc = "7214634";
		$course = "dwada";
		$course = "dwada";
		$date = "19/09/2020";
		$city = "Tacna";
		$email = "my@gmail.com";
		$phone = "993943";
		$payment_way_id = "1";
		$amount = "10";
		$account_name = "mi cuenta";
		return view('emails.notification_entity', compact('logo', 'company_name', 'firstname', 'dni_ruc', 'course', 'date', 'city', 'email', 'phone', 'payment_way_id', 'amount', 'account_name'));
	}

	public function detail_entity($identity_document)
	{
		$entity = Entity::whereIdentityDocument($identity_document)->get();

		if (count($entity)) {
			return response()->json(['success' => true, 'entity' => $entity[0]]);
		}

		return response()->json(['success' => false]);

	}
}
