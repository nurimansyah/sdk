<?php

namespace Flipbox\SDK\Modules\Translation\Drivers;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Flipbox\SDK\Modules\Translation\Contracts\TranslationDriver;

class File implements TranslationDriver
{
    use Concerns\SplitKey,
        Concerns\HasCache;

    /**
     * Cache of loaded translations.
     *
     * @var array
     */
    public static $loaded = [];

    /**
     * Absolute path to Flipbox CMS root directory.
     *
     * @var string
     */
    protected $translationPath = '';

    /**
     * Class constructor.
     *
     * @param string $translationPath
     */
    public function __construct(string $translationPath)
    {
        if (!file_exists($this->translationPath = $translationPath)) {
            throw new InvalidArgumentException("Flipbox CMS translation path is not readable, path: [{$translationPath}].");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function translate(string $key, string $locale, $default = null)
    {
        if (!$key) {
            throw new InvalidArgumentException('Argument locale cannot be empty.');
        }

        if (!$locale) {
            throw new InvalidArgumentException('Argument locale cannot be empty.');
        }

        if ($this->hasTranslated($key, $locale)) {
            return $this->getTranslated($key, $locale);
        }

        [$group, $normalizedKey] = $this->splitKey($key);

        if (!$normalizedKey || !$group) {
            return $default;
        }

        $this->loadFile($locale, $group);

        $result = Arr::get(static::$loaded, "{$locale}.{$group}.{$normalizedKey}", $default);

        $this->setTranslated($key, $locale, $result);

        return $result;
    }

    /**
     * Load file from PHP.
     *
     * @param string $locale
     * @param string $group
     */
    protected function loadFile(string $locale, string $group)
    {
        if ($this->hasLoaded($locale, $group)) {
            return;
        }

        $filePath = "{$this->translationPath}/{$locale}/{$group}.php";

        if (!is_readable($filePath)) {
            return;
        }

        Arr::set(static::$loaded, "{$locale}.{$group}", require($filePath));
    }

    /**
     * Determine if a translation has loaded.
     *
     * @param string $locale
     * @param string $group
     *
     * @return bool
     */
    protected function hasLoaded(string $locale, string $group): bool
    {
        return Arr::has(static::$loaded, "{$locale}.{$group}");
    }
}
