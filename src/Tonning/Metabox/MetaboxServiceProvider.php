<?php 

namespace Tonning\Metabox;

use Illuminate\Support\ServiceProvider;

class MetaboxServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['metabox'] = $this->app->share(function ($app) {
			return new MetaboxBuilder($app['html'], $app['url'], $app['session.store']->getToken());
		});

		$this->app->alias('metabox', 'Tonning\Metabox\MetaboxBuilder');

	}

}
