<?php namespace Arcanedev\LaravelSeo\Redirectors;

use Arcanedev\LaravelSeo\Contracts\Redirector;

/**
 * Class     EloquentRedirector
 *
 * @package  Arcanedev\LaravelSeo\Redirectors
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class EloquentRedirector extends AbstractRedirector implements Redirector
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Get the redirected URLs.
     *
     * @return array
     */
    public function getRedirectedUrls()
    {
        /** @var  \Illuminate\Database\Eloquent\Collection  $redirects */
        $redirects = $this->getRedirectModel()->get();

        return $redirects->keyBy('old_url')
            ->transform(function ($item) {
                return [$item->new_url, $item->status];
            })
            ->toArray();
    }

    /**
     * Get the redirect model.
     *
     * @return \Arcanedev\LaravelSeo\Models\Redirect
     */
    private function getRedirectModel()
    {
        return app(
            $this->getOption('model', \Arcanedev\LaravelSeo\Models\Redirect::class)
        );
    }
}
