<?php namespace Arcanedev\LaravelSeo\Tests;

use Arcanedev\LaravelSeo\Models\Redirect;
use Arcanedev\LaravelSeo\Seo;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class     RedirectorManagerTest
 *
 * @package  Arcanedev\LaravelSeo\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RedirectorManagerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */
    /** @var  \Arcanedev\LaravelSeo\RedirectorManager */
    protected $manager;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = $this->app->make(\Arcanedev\LaravelSeo\Contracts\RedirectorFactory::class);
    }

    public function tearDown()
    {
        unset($this->manager);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\Manager::class,
            \Arcanedev\LaravelSeo\Contracts\RedirectorFactory::class,
            \Arcanedev\LaravelSeo\RedirectorManager::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->manager);
        }

        $this->assertSame('config', $this->manager->getDefaultDriver());

        $this->assertEmpty($this->manager->getDrivers());
    }

    /** @test */
    public function it_can_build_config_driver()
    {
        $driver = $this->manager->driver('config');

        $expectations = [
            \Arcanedev\LaravelSeo\Contracts\Redirector::class,
            \Arcanedev\LaravelSeo\Redirectors\AbstractRedirector::class,
            \Arcanedev\LaravelSeo\Redirectors\ConfigurationRedirector::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $driver);
        }

        $redirects = $driver->getRedirectedUrls();

        $this->assertInternalType('array', $redirects);
        $this->assertCount(2, $redirects);
    }

    /** @test */
    public function it_can_build_eloquent_driver()
    {
        $driver = $this->manager->driver('eloquent');

        $expectations = [
            \Arcanedev\LaravelSeo\Contracts\Redirector::class,
            \Arcanedev\LaravelSeo\Redirectors\AbstractRedirector::class,
            \Arcanedev\LaravelSeo\Redirectors\EloquentRedirector::class,
        ];

        Redirect::createOne('/non-existing-page-url', '/existing-page-url');
        Redirect::createOne('/old-blog/{slug}', '/new-blog/{slug}');

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $driver);
        }

        $redirects = $driver->getRedirectedUrls();

        $this->assertInternalType('array', $redirects);
        $this->assertCount(2, $redirects);

        $expected = [
            '/non-existing-page-url' => ['/existing-page-url', Response::HTTP_MOVED_PERMANENTLY],
            '/old-blog/{slug}'       => ['/new-blog/{slug}', Response::HTTP_MOVED_PERMANENTLY],
        ];

        $this->assertSame($expected, $redirects);
    }

    /** @test */
    public function it_will_not_interfere_with_existing_pages()
    {
        $this->visit('existing-page')
             ->see('existing page');
    }

    /** @test */
    public function it_will_redirect_a_non_existing_page_with_a_permanent_redirect()
    {
        $this->setRedirectUrl([
            'non-existing-page' => 'existing-page',
        ]);

        $this->get('non-existing-page');

        $this->assertRedirectedTo('existing-page');

        $this->assertResponseStatus(Response::HTTP_MOVED_PERMANENTLY);
    }

    /** @test */
    public function it_will_not_redirect_an_url_that_it_not_configured()
    {
        $this->setRedirectUrl([
            'non-existing-page' => '/existing-page',
        ]);

        $this->get('/not-configured');

        $this->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_can_use_named_parameters()
    {
        $this->setRedirectUrl([
            'segment1/{id}/segment2/{slug}' => 'segment2/{slug}',
        ]);

        $this->get('segment1/123/segment2/abc');

        $this->assertRedirectedTo('segment2/abc');
    }

    /** @test */
    public function it_can_use_multiple_named_parameters_in_one_segment()
    {
        $this->setRedirectUrl([
            'new-segment/{id}-{slug}' => 'new-segment/{id}',
        ]);

        $this->get('new-segment/123-blablabla');

        $this->assertRedirectedTo('new-segment/123');
    }

    /** @test */
    public function it_can_optionally_set_the_redirect_status_code()
    {
        $this->setRedirectUrl([
            'temporarily-moved' => ['just-for-now', Response::HTTP_FOUND],
        ]);

        $this->get('temporarily-moved');

        $this->assertRedirectedTo('just-for-now');
        $this->assertResponseStatus(Response::HTTP_FOUND);
    }

    /** @test */
    public function it_can_use_optional_parameters()
    {
        $this->setRedirectUrl([
            'old-segment/{parameter1?}/{parameter2?}' => 'new-segment',
        ]);

        $this->get('old-segment');

        $this->assertRedirectedTo('new-segment');

        $this->get('old-segment/old-segment2');

        $this->assertRedirectedTo('new-segment');

        $this->get('old-segment/old-segment2/old-segment3');

        $this->assertRedirectedTo('new-segment');
    }

    /** @test */
    public function it_will_not_redirect_requests_that_are_not_404s()
    {
        $this->get('response-code/500');

        $this->assertResponseStatus(500);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Set the redirect URL.
     *
     * @param  array  $urls
     */
    private function setRedirectUrl(array $urls)
    {
        $this->app['config']->set(Seo::KEY.'.redirector.drivers.config.options.redirects', $urls);
    }
}
