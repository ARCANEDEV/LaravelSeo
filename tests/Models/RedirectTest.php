<?php namespace Arcanedev\LaravelSeo\Tests\Models;

use Arcanedev\LaravelSeo\Models\Redirect;
use Arcanedev\LaravelSeo\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class     RedirectTest
 *
 * @package  Arcanedev\LaravelSeo\Tests\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RedirectTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */
    /** @test */
    public function it_can_create()
    {
        $redirect = Redirect::createOne(
            $old = '/old-url',
            $new = '/new-url'
        );

        $this->seeInDatabase('seo_redirects', [
            'old_url' => $old,
            'new_url' => $new,
            'status'  => Response::HTTP_MOVED_PERMANENTLY,
        ]);

        // Assert the accessors
        $this->assertSame('[301] Moved Permanently', $redirect->status_name);
    }
}
