<?php

namespace Flipbox\SDK\Modules\Dealer;

use Flipbox\SDK\Modules\Module as BaseModule;
use Flipbox\SDK\Contracts\Module as ModuleContract;
use Flipbox\SDK\Modules\Dealer\Contracts\DealerDriver;

class Module extends BaseModule implements ModuleContract
{
    /**
     * Driver.
     *
     * @var DealerDriver
     */
    protected $driver;

    /**
     * Create driver.
     *
     * @return DealerDriver
     */
    public function driver(): DealerDriver
    {
        if (null !== $this->driver) {
            return $this->driver;
        }

        $driver = new Drivers\Eloquent();

        return $this->driver = $driver;
    }

    /**
     * Return all dealer.
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
     * Get a single dealer.
     *
     * @param int    $id
     * @param string $locale
     *
     * @return array
     */
    public function get(int $id, string $locale = ''): array
    {
        return $this->driver()->get(
            $id,
            $this->determineLocale($locale)
        );
    }

    /**
     * Search a dealer.
     *
     * @param array  $criteria
     * @param string $locale
     *
     * @return array
     */
    public function search(array $criteria, string $locale = ''): array
    {
        return $this->driver()->search(
            $criteria,
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
