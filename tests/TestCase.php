<?php namespace Arcanedev\LaravelSeo\Tests;

use Arcanedev\LaravelSeo\Seo;
use Orchestra\Testbench\BrowserKit\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelSeo\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            \Arcanedev\LaravelSeo\LaravelSeoServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            //
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        Seo::setConfig('redirector.drivers.config.options.redirects', [
            '/non-existing-page-url' => '/existing-page-url',
            '/old-blog/{slug}'       => '/new-blog/{slug}',
        ]);

        $this->setUpRoutes($app['router']);
    }

    /**
     * Setup the routes.
     *
     * @param  \Illuminate\Routing\Router  $router
     */
    private function setUpRoutes($router)
    {
        $router->get('existing-page', function () {
            return 'existing page';
        });

        $router->get('response-code/{code}', function ($code) {
            abort($code);
        });
    }

    /* -----------------------------------------------------------------
     |  Custom Assert Methods
     | -----------------------------------------------------------------
     */
    /**
     * Assert whether the client was redirected to a given URI.
     *
     * @param  string  $uri
     * @param  array   $with
     *
     * @return self
     */
    public function assertRedirectedTo($uri, $with = [])
    {
        self::assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $this->response);
        self::assertEquals($this->app['url']->to($uri), $this->response->headers->get('Location'));

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Migrate the tables.
     */
    protected function migrate()
    {
        $paths = array_map('realpath', [
            __DIR__.'/../database/migrations',
            __DIR__.'/fixtures/migrations',
        ]);

        foreach ($paths as $path) {
            $this->artisan('migrate', [
                '--database' => 'testbench',
                '--realpath' => $path,
            ]);
        }
    }
}
