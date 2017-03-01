<?php namespace Arcanedev\LaravelSeo\Bases;

use Arcanedev\Support\Bases\Migration as BaseMigration;

/**
 * Class     Migration
 *
 * @package  Arcanedev\LaravelSeo\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Migration extends BaseMigration
{
    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Migration constructor.
     */
    public function __construct()
    {
        $this->setConnection(config('seo.database.connection', null));
        $this->setPrefix(config('seo.database.prefix', 'seo_'));
    }
}
