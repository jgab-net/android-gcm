<?php namespace JgabNet\AndroidGcm;

use Illuminate\Support\ServiceProvider;

class AndroidGcmServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    public function boot()
    {
        $this->package('jgab-net/android-gcm');
    }

    /**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app['androidgcm'] = $this->app->share(function($app){
           return new AndroidGcm($app['config']->get('android-gcm::api_key', null),$app['config']->get('android-gcm::api_url', null));
        });

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('AndroidGcm', 'JgabNet\Support\Facades\AndroidGcm');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('androidGcm');
	}

}
