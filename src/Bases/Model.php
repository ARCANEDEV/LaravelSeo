<?php namespace Arcanedev\LaravelSeo\Bases;

use Arcanedev\Support\Bases\Model as BaseModel;

/**
 * Class     Model
 *
 * @package  Arcanedev\LaravelSeo\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Model extends BaseModel
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('laravel-seo.database.connection', null));
        $this->setPrefix(config('laravel-seo.database.prefix', null));

        parent::__construct($attributes);
    }
}
