<?php

namespace sisVentas\Http\Controllers\Landing;

use Illuminate\Routing\Controller;
use sisVentas\Articulo;
use sisVentas\Categoria;

class FixerController extends Controller {

	public function fix_product() {
		$products = Articulo::all();

		foreach ($products as $key => $product) {
			$product->slug = str_slug($product->nombre);
			$product->price = rand(10, 20);
			$product->save();
		}
		return;

	}

	public function fix_category() {

		$categories = Categoria::all();

		foreach ($categories as $key => $category) {
			$category->slug = str_slug($category->nombre);
			$category->save();
		}
		return;

	}
}
