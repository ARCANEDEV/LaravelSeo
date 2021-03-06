<?php namespace Arcanedev\LaravelSeo\Tests\Models;

use Arcanedev\LaravelSeo\Tests\Stubs\Models\Post;
use Arcanedev\LaravelSeo\Tests\TestCase;
use Illuminate\Support\Collection;

/**
 * Class     SeoTest
 *
 * @package  Arcanedev\LaravelSeo\Tests\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SeoTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_create()
    {
        $post = $this->createPost();

        $this->assertFalse($post->hasSeo());

        $post->createSeo([
            'title'       => 'Post Title (SEO)',
            'description' => 'Post description (SEO)',
            'extras'      => [
                'twitter:title'       => 'Post title for twitter card',
                'twitter:description' => 'Post description for twitter card',
            ],
        ]);

        $post = $post->fresh();

        $this->assertTrue($post->hasSeo());

        $seo = $post->seo;

        $this->assertDatabaseHas('seo_metas', [
            'id'           => $seo->id,
            'seoable_id'   => 1,
            'seoable_type' => Post::class,
            'title'        => 'Post Title (SEO)',
            'description'  => 'Post description (SEO)',
            'noindex'      => 0
        ]);

        $seo = $seo->fresh();

        $this->assertInstanceOf(Collection::class, $seo->extras);
        $this->assertCount(2, $seo->extras);
        $this->assertSame('Post title for twitter card',       $seo->extras->get('twitter:title'));
        $this->assertSame('Post description for twitter card', $seo->extras->get('twitter:description'));
        $this->assertFalse($seo->noindex);
    }

    /** @test */
    public function it_can_update()
    {
        /** @var \Arcanedev\LaravelSeo\Tests\Stubs\Models\Post $post */
        $post = $this->createPost();

        $this->assertFalse($post->hasSeo());

        $post->createSeo([
            'title'       => 'Post Title (SEO)',
            'description' => 'Post description (SEO)',
        ]);

        $post = $post->fresh();

        $this->assertTrue($post->hasSeo());

        // Update
        $post->updateSeo([
            'title' => 'Updated Post Title (SEO)',
        ]);

        $post = $post->fresh();

        $this->assertTrue($post->hasSeo());
        $this->assertSame('Updated Post Title (SEO)', $post->seo->title);
        $this->assertSame('Post description (SEO)', $post->seo->description);
    }

    /** @test */
    public function it_can_delete()
    {
        /** @var \Arcanedev\LaravelSeo\Tests\Stubs\Models\Post $post */
        $post = $this->createPost();

        $this->assertFalse($post->hasSeo());

        $post->createSeo([
            'title'       => 'Post Title (SEO)',
            'description' => 'Post description (SEO)',
        ]);

        $post = $post->fresh();

        $this->assertTrue($post->hasSeo());

        $post->deleteSeo();

        $post = $post->fresh();

        $this->assertFalse($post->hasSeo());
    }

    /** @test */
    public function it_can_get_seoable()
    {
        /** @var \Arcanedev\LaravelSeo\Tests\Stubs\Models\Post $post */
        $post = $this->createPost();

        $post->createSeo([
            'title'       => 'Post Title (SEO)',
            'description' => 'Post description (SEO)',
        ]);

        $post = $post->fresh();

        $this->assertTrue($post->hasSeo());

        $seo = $post->seo;

        $seoable = $seo->seoable;

        $this->assertInstanceOf(Post::class, $seoable);
        $this->assertEquals($post->id,       $seoable->id);
        $this->assertEquals($post->title,    $seoable->title);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create post for test.
     *
     * @return \Arcanedev\LaravelSeo\Tests\Stubs\Models\Post
     */
    public function createPost()
    {
        return Post::create([
            'title'   => 'Post title',
            'content' => 'Post content',
        ]);
    }
}
