<?php

use Arcanedev\LaravelSeo\Bases\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class     CreateSeoRedirectsTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateSeoRedirectsTable extends Migration
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * CreateSeoMetasTable constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setTable('redirects');
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
            $table->string('old_url', 255);
            $table->string('new_url', 255);
            $table->integer('status');
            $table->timestamps();

            $table->unique('old_url');
        });
    }
}
