<?php namespace Airavata\DataManager;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Airavata\Data\Manager\Cpi;
use Thrift\Transport\TSocket;
use Thrift\Protocol\TBinaryProtocol;
use Illuminate\Routing\Redirector;

class DataManagerServiceProvider extends ServiceProvider {

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
        $this->package('airavata/data-manager');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        //registering service provider
        $this->app['data-manager'] = $this->app->share(function($app)
        {
            try{
                $transport = new TSocket(Config::get('pga_config.airavata')['data-manager-server'], Config::get('pga_config.airavata')['data-manager-port']);
                $transport->setRecvTimeout(Config::get('pga_config.airavata')['data-manager-timeout']);
                $transport->setSendTimeout(Config::get('pga_config.airavata')['data-manager-timeout']);

                $protocol = new TBinaryProtocol($transport);
                $transport->open();

                $client = new Cpi\DataManagerServiceClient($protocol);

            }catch (\Exception $ex){
                throw new \Exception("Unable to instantiate Data Manager Client", 0,  $ex);
            }

            if( is_object( $client))
                return $client;
            else
                throw new \Exception("Unable to instantiate Data Manager Client");
        });

        //registering alias
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('DataManager', 'Airavata\DataManager\Facades\DataManager');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('data-manager');
	}

}
