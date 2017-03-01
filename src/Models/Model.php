<?php namespace Arcanedev\LaravelSeo\Models;

use Arcanedev\Support\Bases\Model as BaseModel;

/**
 * Class     Model
 *
 * @package  Arcanedev\LaravelSeo\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Model extends BaseModel
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
        parent::__construct($attributes);

        $this->setConnection(config('seo.database.connection', null));
        $this->setPrefix(config('seo.database.prefix', 'seo_'));
    }
}
