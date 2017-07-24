<?php namespace Arcanedev\LaravelSeo\Tests\Entities;

use Arcanedev\LaravelSeo\Entities\RedirectStatuses;
use Arcanedev\LaravelSeo\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class     RedirectStatusesTest
 *
 * @package  Arcanedev\LaravelSeo\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RedirectStatusesTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_get_all_keys()
    {
        $codes = RedirectStatuses::keys();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $codes);

        $this->assertCount(4, $codes);
        $this->assertSame($this->getSupportedCodes(), $codes->toArray());
    }

    /** @test */
    public function it_can_get_all_names()
    {
        $redirections = RedirectStatuses::all();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $redirections);

        $this->assertCount(4, $redirections);

        $this->assertSame([
            301 => '[301] Moved Permanently',
            303 => '[302] See Other',
            307 => '[307] Temporary Redirect',
            308 => '[308] Permanent Redirect',
        ], $redirections->toArray());
    }

    /** @test */
    public function it_can_get_all_translated_names()
    {
        $locales = [
            'en' => [
                301 => '[301] Moved Permanently',
                303 => '[302] See Other',
                307 => '[307] Temporary Redirect',
                308 => '[308] Permanent Redirect',
            ],
            'fr' => [
                301 => '[301] Déplacé de manière permanente',
                303 => '[302] Voir autres',
                307 => '[307] Redirection temporaire',
                308 => '[308] Redirection permanente',
            ],
        ];

        foreach ($locales as $locale => $expected) {
            $redirections = RedirectStatuses::all($locale);

            $this->assertInstanceOf(\Illuminate\Support\Collection::class, $redirections);

            $this->assertCount(4, $redirections);

            $this->assertSame($expected, $redirections->toArray());
        }
    }

    /** @test */
    public function it_can_get_by_status_code()
    {
        $statuses = [
            301 => '[301] Moved Permanently',
            303 => '[302] See Other',
            307 => '[307] Temporary Redirect',
            308 => '[308] Permanent Redirect',
        ];

        foreach ($statuses as $code => $status) {
            $this->assertSame($status, RedirectStatuses::get($code));
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the supported redirection codes.
     *
     * @return array
     */
    protected function getSupportedCodes()
    {
        return [
            Response::HTTP_MOVED_PERMANENTLY,
            Response::HTTP_SEE_OTHER,
            Response::HTTP_TEMPORARY_REDIRECT,
            Response::HTTP_PERMANENTLY_REDIRECT,
        ];
    }
}
