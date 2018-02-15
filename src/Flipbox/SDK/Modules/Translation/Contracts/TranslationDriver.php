<?php

namespace Flipbox\SDK\Modules\Translation\Contracts;

interface TranslationDriver
{
    /**
     * Translate a key.
     *
     * @param string       $key
     * @param string       $locale
     * @param string|array $default
     *
     * @return string|array
     */
    public function translate(string $key, string $locale, $default = null);
}
