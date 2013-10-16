<?php namespace Devtown\Bitbucket;

use Illuminate\Support\ServiceProvider;

class BitbucketServiceProvider extends ServiceProvider {

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
		$this->package('devtown/bitbucket');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['bitbucketapi'] = $this->app->share(function($app)
		{
			$config = $app['config']->get('bitbucket::api');
			$connection = new GuzzleConnection();
			$bb = new Bitbucket($connection);
			
			$bb->auth($config['username'], $config['password']);
			return $bb;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Bitbucket');
	}

}