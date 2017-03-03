<?php namespace Arcanedev\LaravelSeo\Providers;

use Arcanedev\LaravelSeo\Http\Middleware\RedirectsMissingPages;
use Arcanedev\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Contracts\Http\Kernel as HttpKernel;

/**
 * Class     RouteServiceProvider
 *
 * @package  Arcanedev\LaravelSeo\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    public function boot()
    {
        if (config('seo.redirector.enabled', false))
            $this->app->make(HttpKernel::class)->pushMiddleware(RedirectsMissingPages::class);

        parent::boot();
    }
}
