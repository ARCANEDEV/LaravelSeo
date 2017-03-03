<?php namespace Arcanedev\LaravelSeo;

use Arcanedev\Support\PackageServiceProvider;

/**
 * Class     LaravelSeoServiceProvider
 *
 * @package  Arcanedev\LaravelSeo
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class LaravelSeoServiceProvider extends PackageServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'seo';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerConfig();

        $this->singleton(Contracts\RedirectorFactory::class, function ($app) {
            return new RedirectorManager($app);
        });
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        $this->registerProvider(Providers\RouteServiceProvider::class);

        $this->publishConfig();

        $this->loadMigrations();
        //$this->publishMigrations();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            //
        ];
    }
}
