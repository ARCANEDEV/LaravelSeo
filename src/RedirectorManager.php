<?php namespace Arcanedev\LaravelSeo;

use Arcanedev\LaravelSeo\Contracts\RedirectorFactory;
use Illuminate\Support\Manager;

/**
 * Class     RedirectorManager
 *
 * @package  Arcanedev\LaravelSeo
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RedirectorManager extends Manager implements RedirectorFactory
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config()->get('seo.redirector.default', 'config');
    }

    /**
     * Get the config repository.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    protected function config()
    {
        return $this->app['config'];
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     *
     * @return \Arcanedev\LaravelSeo\Contracts\Redirector
     */
    public function driver($driver = null)
    {
        return parent::driver($driver);
    }

    /**
     * Build the config redirector driver.
     *
     * @return \Arcanedev\LaravelSeo\Redirectors\ConfigurationRedirector
     */
    public function createConfigDriver()
    {
        return $this->buildDriver('config');
    }

    /**
     * Build the eloquent redirector driver.
     *
     * @return \Arcanedev\LaravelSeo\Redirectors\EloquentRedirector
     */
    public function createEloquentDriver()
    {
        return $this->buildDriver('eloquent');
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Build the redirector.
     *
     * @param  string  $driver
     *
     * @return \Arcanedev\LaravelSeo\Contracts\Redirector
     */
    private function buildDriver($driver)
    {
        $router  = $this->app->make(\Illuminate\Contracts\Routing\Registrar::class);
        $class   = $this->config()->get("seo.redirector.drivers.$driver.class");
        $options = $this->config()->get("seo.redirector.drivers.$driver.options", []);

        return new $class($router, $options);
    }
}
