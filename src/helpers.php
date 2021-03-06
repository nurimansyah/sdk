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
     * Get all menu.
     *
     * @param string $locale
     *
     * @return array
     */
    function menu(string $locale = ''): array
    {
        return sdk('menu')->all($locale);
    }
}

if (!function_exists('banner')) {
    /**
     * Get banner. Can return multiple or single. Determined by argument data type.
     *
     * @param string|int $arg
     * @param string     $locale
     *
     * @return array
     */
    function banner($arg = null, string $locale = null): array
    {
        return (is_int($arg))
            ? sdk('banner')->get($arg, $locale)
            : sdk('banner')->all($arg);
    }
}

if (!function_exists('dealer')) {
    /**
     * Get dealer. Can return multiple or single. Determined by argument data type.
     *
     * @param string|int $arg
     * @param string     $locale
     *
     * @return array
     */
    function dealer($arg = null, string $locale = null): array
    {
        return (is_int($arg))
            ? sdk('dealer')->get($arg, $locale)
            : sdk('dealer')->all($arg);
    }
}
