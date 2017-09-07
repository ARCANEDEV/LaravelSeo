<?php

use Arcanedev\LaravelSeo\Bases\Migration;
use Arcanedev\LaravelSeo\Seo;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateSeoMetasTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @see \Arcanedev\LaravelSeo\Models\Meta
 */
class CreateSeoMetasTable extends Migration
{
    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * CreateSeoMetasTable constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setTable(Seo::getConfig('metas.table', 'metas'));
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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
            $table->boolean('noindex')->default(false);
            $table->text('extras')->nullable();
            $table->timestamps();
        });
    }
}
