<?php

namespace Flipbox\SDK\Modules\Translation;

use Flipbox\SDK\Modules\Module as BaseModule;
use Flipbox\SDK\Contracts\Module as ModuleContract;
use Flipbox\SDK\Modules\Translation\Contracts\TranslationDriver;

class Module extends BaseModule implements ModuleContract
{
    /**
     * Driver.
     *
     * @var TranslationDriver
     */
    protected $driver;

    /**
     * Translate a string.
     *
     * @param string       $key
     * @param string|array $default
     * @param string       $locale
     *
     * @return string|array
     */
    public function translate(string $key, $default = null, string $locale = '')
    {
        $locale = $this->determineLocale($locale);

        return $this->driver()->translate($key, $locale, $default);
    }

    /**
     * Create driver.
     *
     * @return TranslationDriver
     */
    public function driver(): TranslationDriver
    {
        if (null !== $this->driver) {
            return $this->driver;
        }

        if ($cmsPath = config('flipbox-sdk.modules.translation.drivers.file.path')) {
            $driver = new Drivers\File($cmsPath);
        } else {
            $driver = new Drivers\Eloquent();
        }

        return $this->driver = $driver;
    }

    /**
     * Clear resolved driver, force to re-create when needed.
     *
     * @return self
     */
    public function clear(): self
    {
        $this->driver = null;

        return $this;
    }

    /**
     * Clear resolved driver and translated cache.
     *
     * @return self
     */
    public function flush(): self
    {
        $this->clear();

        Drivers\File::$loaded = [];
        Drivers\File::$translated = [];

        Drivers\Eloquent::$translated = [];

        return $this;
    }
}
