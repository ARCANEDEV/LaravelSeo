<?php

use Arcanedev\Support\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateSeoTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateSeoTable extends Migration
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'seo';

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * CreateSeoTable constructor.
     */
    public function __construct()
    {
        $this->setConnection(config('laravel-seo.database.connection', null));
        $this->setPrefix(config('laravel-seo.database.prefix', null));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createSchema(function(Blueprint $table) {
            $table->increments('id');
            $table->morphs('seoable');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->text('metas')->nullable();
            $table->boolean('noindex')->default(false);
            $table->timestamps();
        });
    }
}
