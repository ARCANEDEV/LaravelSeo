<?php

use Arcanedev\LaravelSeo\Bases\Migration;
use Arcanedev\LaravelSeo\Seo;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateSeoRedirectsTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @see \Arcanedev\LaravelSeo\Models\Redirect
 */
class CreateSeoRedirectsTable extends Migration
{
    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * CreateSeoRedirectsTable constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setTable(Seo::getConfig('redirects.table', 'redirects'));
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
            $table->string('old_url', 255);
            $table->string('new_url', 255);
            $table->integer('status');
            $table->timestamps();

            $table->unique('old_url');
        });
    }
}
