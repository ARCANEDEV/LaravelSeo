<?php namespace Arcanedev\LaravelSeo\Tests\Models;

use Arcanedev\LaravelSeo\Models\Meta;
use Arcanedev\LaravelSeo\Tests\Stubs\Models\Post;
use Arcanedev\LaravelSeo\Tests\TestCase;
use Illuminate\Support\Collection;

/**
 * Class     MetaTest
 *
 * @package  Arcanedev\LaravelSeo\Tests\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MetaTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->migrate();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_create()
    {
        $post = $this->createPost();

        $this->assertFalse($post->hasSeo());

        $post->createSeo([
            'title'       => 'Post Title (SEO)',
            'description' => 'Post description (SEO)',
            'keywords'    => ['keyword-1', 'keyword-2', 'keyword-3', 'keyword-4'],
            'metas'       => [
                'twitter:title'       => 'Post title for twitter card',
                'twitter:description' => 'Post description for twitter card',
            ],
        ]);

        $post = $post->fresh();

        $this->assertTrue($post->hasSeo());

        $seo = $post->seo;

        $this->seeInDatabase('seo_metas', [
            'id'           => $seo->id,
            'seoable_id'   => 1,
            'seoable_type' => Post::class,
            'title'        => 'Post Title (SEO)',
            'description'  => 'Post description (SEO)',
            'keywords'     => json_encode(['keyword-1', 'keyword-2', 'keyword-3', 'keyword-4']),
            'noindex'      => 0
        ]);

        $seo = $seo->fresh();

        $this->assertInstanceOf(Collection::class, $seo->metas);
        $this->assertCount(2, $seo->metas);
        $this->assertSame('Post title for twitter card',       $seo->metas->get('twitter:title'));
        $this->assertSame('Post description for twitter card', $seo->metas->get('twitter:description'));
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
            'title'    => 'Updated Post Title (SEO)',
            'keywords' => ['keyword-1', 'keyword-2', 'keyword-3', 'keyword-4'],
        ]);

        $post = $post->fresh();

        $this->assertTrue($post->hasSeo());
        $this->assertSame('Updated Post Title (SEO)', $post->seo->title);
        $this->assertSame('Post description (SEO)', $post->seo->description);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $post->seo->keywords);
        $this->assertCount(4, $post->seo->keywords);
        $this->assertSame(['keyword-1', 'keyword-2', 'keyword-3', 'keyword-4'], $post->seo->keywords->toArray());
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

    /** @test */
    public function it_can_get_accessors()
    {
        $meta = new Meta([
            'title'       => 'Post Title (SEO)',
            'description' => 'Post description (SEO)',
            'keywords'    => ['keyword-1', 'keyword-2', 'keyword-3', 'keyword-4'],
        ]);

        $this->assertSame(16, $meta->title_length);
        $this->assertSame(22, $meta->description_length);
        $this->assertSame(4,  $meta->keywords->count());
        $this->assertSame(
            'keyword-1, keyword-2, keyword-3, keyword-4',
            $meta->keywords_string
        );
        $this->assertSame(42, $meta->keywords_length);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
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
