<?php namespace Arcanedev\LaravelSeo;

/**
 * Class     Seo
 *
 * @package  Arcanedev\LaravelSeo
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Seo
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */
    const KEY = 'laravel-seo';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Get the seo config.
     *
     * @param  string      $key
     * @param  mixed|null  $default
     *
     * @return mixed
     */
    public static function getConfig($key, $default = null)
    {
        return config(self::KEY.'.'.$key, $default);
    }

    /**
     * Get the seo translation.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     *
     * @return string
     */
    public static function getTrans($key = null, $replace = [], $locale = null)
    {
        return trans(self::KEY.'::'.$key, $replace, $locale);
    }
}
