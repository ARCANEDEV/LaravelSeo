<?php namespace Arcanedev\LaravelSeo\Tests\Stubs\Models;

use Arcanedev\LaravelSeo\Traits\Seoable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class     Post
 *
 * @package  Arcanedev\LaravelSeo\Tests\Stubs\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Post extends Model
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use Seoable;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
    ];
}
