<?php

use Flipbox\SDK\Factory;

if (!function_exists('sdk')) {
    /**
     * Get the SDK factory or an SDK module.
     *
     * @param string $moduleName
     *
     * @return Factory|Module
     */
    function sdk(string $moduleName = '')
    {
        $factory = app(Factory::class);

        if (!$moduleName) {
            return $factory;
        }

        return app(Factory::class)->resolve($moduleName);
    }
}

if (!function_exists('translate')) {
    /**
     * Translate a key.
     *
     * @param string       $key
     * @param string|array $default
     * @param string       $locale
     *
     * @return string|array
     */
    function translate(string $key, $default = null, string $locale = '')
    {
        return sdk('translation')->translate($key, $default, $locale);
    }
}

if (!function_exists('menu')) {
    /**
     * Translate a key.
     *
     * @param string       $key
     * @param string|array $default
     * @param string       $locale
     *
     * @return string|array
     */
    function menu()
    {
        return sdk('menu')->all();
    }
}

if (!function_exists('searchMenu')) {
    /**
     * search a all menu.
     *
     * @param string|array $default
     *
     * @return string|array
     */
    function searchMenu($default = null)
    {
        return sdk('menu')->search($default);
    }
}

if (!function_exists('banner')) {
    /**
     * get all banner a key.
     *
     * @param string       $key
     * @param string|array $default
     * @param string       $locale
     *
     * @return string|array
     */
    function banner()
    {
        return sdk('banner')->all();
    }
}

if (!function_exists('searchBanner')) {
    /**
     * search a all banner.
     *
     * @param string|array $default
     *
     * @return string|array
     */
    function searchBanner($default = null)
    {
        return sdk('banner')->search($default);
    }
}

if (!function_exists('findBanner')) {
    /**
     * find by banner by parameter.
     *
     * @param string $findBanner
     *
     * @return object
     */
    function findBanner($param)
    {
        return sdk('banner')->find($param);
    }
}