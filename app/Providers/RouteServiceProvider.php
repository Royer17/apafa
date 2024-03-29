<?php

namespace sisVentas\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider {
	/**
	 * This namespace is applied to your controller routes.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'sisVentas\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router) {
		//

		parent::boot($router);
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router) {
		$this->mapWebRoutes($router);
		$this->mapApiRoutes($router);

		//
	}

	/**
	 * Define the "web" routes for the application.
	 *
	 * These routes all receive session state, CSRF protection, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	protected function mapWebRoutes(Router $router) {
		$router->group([
			'namespace' => $this->namespace, 'middleware' => 'web',
		], function ($router) {
			require app_path('Http/routes.php');
		});
	}

	protected function mapApiRoutes(Router $router) {
		$router->group([
			'middleware' => 'api',
			'namespace' => $this->namespace,
			'prefix' => 'api',
		], function ($router) {
			require base_path('routes/api.php');
		});
	}

}
