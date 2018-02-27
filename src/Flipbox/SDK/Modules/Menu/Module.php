<?php

namespace Flipbox\SDK\Modules\Menu;

use Flipbox\SDK\Modules\Module as BaseModule;
use Flipbox\SDK\Modules\Menu\Contracts\MenuDriver;
use Flipbox\SDK\Contracts\Module as ModuleContract;

class Module extends BaseModule implements ModuleContract
{
    /**
     * Driver.
     *
     * @var TranslationDriver
     */
    protected $driver;

    /**
     * Create driver.
     *
     * @return TranslationDriver
     */
    public function driver(): MenuDriver
    {
        if (null !== $this->driver) {
            return $this->driver;
        }

        $driver = new Drivers\Eloquent();

        return $this->driver = $driver;
    }

    /**
     * Please describe process of this method.
     *
     * @param string $locale
     *
     * @return array
     */
    public function all(string $locale = ''): array
    {
        return $this->driver()->all(
            $this->determineLocale($locale)
        );
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
}
