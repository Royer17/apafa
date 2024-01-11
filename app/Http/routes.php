<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::group(['namespace' => 'Landing'], function () {
	Route::get('/', 'HomeController@index');
	Route::get('/productos', 'HomeController@view_products');
	Route::get('/producto/{slug}', 'HomeController@view_product_profile');

	Route::get('/detalles-documento', function () {
		return redirect('/busqueda-documento');
	});

	Route::get('/email-view', 'HomeController@email_view');

	Route::post('/detalles-documento', 'OrderController@details_document_view');
	Route::get('/nosotros', 'HomeController@view_about_us');
	Route::post('/search', 'OrderController@search');
	Route::get('/busqueda-documento', 'HomeController@view_order');
	Route::post('/solicitud-enviada', 'OrderController@request_completed');


	Route::get('/resumen-de-la-orden/{id}', 'OrderController@view_order');
	Route::post('/order', 'OrderController@store');
	Route::post('/logged-solicitude', 'OrderController@store_logged_solicitude');

	Route::put('/order-confirm/{id}', 'OrderController@confirm');

	Route::get('/cart-detail', 'ProductController@cart_detail');
	Route::get('/cart-total', 'ProductController@cart_total');
	Route::get('/cart-summary', 'ProductController@cart_summary');
	Route::get('/document-state/{id}', 'HomeController@get_document_state');

	Route::get('/product/search', 'ProductController@search');

	Route::get('/fix-product', 'FixerController@fix_product');
	Route::get('/fix-category', 'FixerController@fix_category');
	Route::get('/products/paginated', 'ProductController@all_paginated');

	Route::get('/entity/{document}/detail', 'HomeController@detail_entity');
});

Route::get('/admin', function () {
	return view('auth/login');
});
Route::get('/acerca', function () {
	return view('acerca');
});

Route::resource('almacen/categoria', 'CategoriaController');
Route::resource('/admin/profesiones', 'ProfessionController');
Route::resource('/admin/oficinas', 'OfficeController');
Route::resource('/admin/solicitudes', 'SolicitudeController');
Route::delete('/admin/solicitude/{id}', 'SolicitudeController@delete_solicitude');

Route::resource('/admin/mis-solicitudes-enviadas', 'SolicitudeController@my_solicitude_sent_view');
Route::get('/admin/crear-solicitud', 'SolicitudeController@create_solicitude');

// Route::post('/admin/ruta-de-solicitud', 'SolicitudeController@report');
Route::post('/admin/ruta-de-solicitud', 'SolicitudeController@details_document_view');

// Route::post('/admin/detalles-documento', 'SolicitudeController@details_document_view');

Route::resource('/admin/personal', 'EntityController');
Route::post('/admin/solicitude-status', 'SolicitudeController@update_status');

Route::resource('almacen/articulo', 'ArticuloController');
Route::resource('ventas/cliente', 'ClienteController');
Route::resource('compras/proveedor', 'ProveedorController');
Route::resource('compras/ingreso', 'IngresoController');
Route::resource('ventas/venta', 'VentaController');
Route::resource('seguridad/usuario', 'UsuarioController');

Route::post('/products-import', 'ArticuloController@import');

Route::auth();

// Route::get('/home', 'HomeController@index');
Route::get('/empresa', 'HomeController@showCompany')->name('company.show');
Route::post('/empresa', 'HomeController@updateCompany')->name('company.update');

// //Excel
// Route::get('excelventas', 'VentaController@reporteExcel');
// Route::get('excelingresos', 'IngresoController@reporteExcel');

// //Reportes
// Route::get('reportecategorias', 'CategoriaController@reporte');
// Route::get('reportearticulos', 'ArticuloController@reporte');
// Route::get('reporteclientes', 'ClienteController@reporte');
// Route::get('reporteproveedores', 'ProveedorController@reporte');
// Route::get('reporteventas', 'VentaController@reporte');
// Route::get('reporteventa/{id}', 'VentaController@reportec');
// Route::get('reporteingresos', 'IngresoController@reporte');
// Route::get('reporteingreso/{id}', 'IngresoController@reportec');
// Route::get('/{slug?}', 'HomeController@index');
