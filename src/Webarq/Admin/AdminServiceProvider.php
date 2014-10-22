<?php namespace Webarq\Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('webarq/admin');

		\App::bind('admin\html', function()
		{
			return new Html();
		});
		include __DIR__.'/../../helpers.php';
		
		\Route::filter('admin_auth', function()
		{
			if (\Request::segment(2) !== 'auth')
			{
				$admin = new Admin();
				$urlPrefix = $admin->getUrlPrefix();

				if (\Auth::guest() || ! \Auth::user()->admin)
				{
					return \Redirect::to($urlPrefix.'/auth/login');
				}

				if (\Auth::user()->admin->role_id > 1)
				{
					$roleRoutes = \Auth::user()->admin->role->routes->lists('route', 'id');

					// Get section from the requested URL
					$section = str_replace($urlPrefix.'/', '', \Request::path());

					if (\Request::segment(2) && ! (in_array($section, $roleRoutes)))
					{
						\App::abort(401, 'You are not authorized.');
					}
				}
			}
		});
		
		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['admin'] = $this->app->share(function($app)
		{
			return new Admin;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('admin');
	}

}