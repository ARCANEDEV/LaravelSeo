<?php namespace Arcanedev\LaravelSeo\Models;

/**
 * Class     Meta
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
class Meta extends AbstractModel
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
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
        parent::__construct($attributes);

        $this->setTable(config('laravel-seo.database.table', 'metas'));
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
