<?php namespace Wsis;

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
        $this->package('wsis/wsis');
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
                Config::get('pga_config.wsis')['admin-username'] . "@" . Config::get('pga_config.wsis')['tenant-domain'],
                Config::get('pga_config.wsis')['admin-password'],
                Config::get('pga_config.wsis')['server'],
                Config::get('pga_config.wsis')['service-url'],
                Config::get('pga_config.wsis')['cafile-path'],
                Config::get('pga_config.wsis')['verify-peer'],
                Config::get('pga_config.wsis')['allow-self-signed-cert']
            );
        });

        //registering alis
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('WSIS', 'Wsis\Facades\Wsis');
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
