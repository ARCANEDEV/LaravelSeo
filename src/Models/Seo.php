<?php namespace Arcanedev\LaravelSeo\Models;

use Arcanedev\LaravelSeo\Bases\Model;

/**
 * Class     Seo
 *
 * @package  Arcanedev\LaravelSeo\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int                                  id
 * @property  int                                  seoable_id
 * @property  string                               seoable_type
 * @property  string                               title
 * @property  string                               description
 * @property  string                               keywords
 * @property  \Illuminate\Support\Collection       metas
 * @property  boolean                              noindex
 * @property  \Carbon\Carbon                       created_at
 * @property  \Carbon\Carbon                       updated_at
 *
 * @property  \Illuminate\Database\Eloquent\Model  seoable
 */
class Seo extends Model
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'keywords', 'metas', 'noindex'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'         => 'integer',
        'seoable_id' => 'integer',
        'metas'      => 'collection',
        'noindex'    => 'boolean',
    ];

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
        $this->setTable(config('laravel-seo.database.table', 'seo'));

        parent::__construct($attributes);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Relationships
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function seoable()
    {
        return $this->morphTo();
    }
}
