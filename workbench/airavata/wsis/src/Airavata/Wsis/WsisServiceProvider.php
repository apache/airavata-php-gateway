<?php namespace Airavata\Wsis;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class WsisServiceProvider extends ServiceProvider {

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
        $this->package('airavata/wsis');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        //registering service provider
        $this->app['wsis'] = $this->app->share(function($app)
        {
            return new Wsis(
                Config::get('wsis::admin-username'),
                Config::get('wsis::admin-password'),
                Config::get('wsis::server'),
                Config::get('wsis::service-url'),
                Config::get('wsis::cafile-path'),
                Config::get('wsis::verify-peer'),
                Config::get('wsis::allow-selfsigned-cert')
            );
        });

        //registering alis
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('WSIS', 'Airavata\Wsis\Facades\Wsis');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('wsis');
	}

}
