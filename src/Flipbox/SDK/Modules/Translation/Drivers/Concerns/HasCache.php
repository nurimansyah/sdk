<?php

namespace Flipbox\SDK\Modules\Translation\Drivers\Concerns;

trait HasCache
{
    /**
     * Translated translations.
     *
     * @var array
     */
    public static $translated = [];

    /**
     * Determine if a translation has translated.
     *
     * @param string $key
     * @param string $locale
     *
     * @return bool
     */
    protected function hasTranslated(string $key, string $locale): bool
    {
        return array_key_exists("{$locale}.{$key}", static::$translated);
    }

    /**
     * Get translated translation from cache.
     *
     * @param string $key
     * @param string $locale
     *
     * @return string|array
     */
    protected function getTranslated(string $key, string $locale)
    {
        return static::$translated["{$locale}.{$key}"];
    }

    /**
     * Cache a translated translation.
     *
     * @param string       $key
     * @param string       $locale
     * @param string|array $value
     */
    protected function setTranslated(string $key, string $locale, $value)
    {
        static::$translated["{$locale}.{$key}"] = $value;
    }
}
