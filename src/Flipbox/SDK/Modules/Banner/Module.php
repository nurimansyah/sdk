<?php

namespace Flipbox\SDK\Modules\Banner;

use Flipbox\SDK\Modules\Module as BaseModule;
use Flipbox\SDK\Contracts\Module as ModuleContract;
use Flipbox\SDK\Modules\Banner\Contracts\BannerDriver;

class Module extends BaseModule implements ModuleContract
{
    /**
     * Driver.
     *
     * @var BannerDriver
     */
    protected $driver;

    /**
     * Create driver.
     *
     * @return BannerDriver
     */
    public function driver(): BannerDriver
    {
        if (null !== $this->driver) {
            return $this->driver;
        }

        $driver = new Drivers\Eloquent();

        return $this->driver = $driver;
    }

    /**
     * {@inheritdoc}
     */
    public function all(string $locale = ''): array
    {
        return $this->driver()->all(
            $this->determineLocale($locale)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id, string $locale = ''): array
    {
        return $this->driver()->get(
            $id,
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
