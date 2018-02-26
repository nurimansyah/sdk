<?php

namespace Flipbox\SDK\Modules\Banner;

use Flipbox\SDK\Contracts\Module as ModuleContract;
use Flipbox\SDK\Modules\Banner\Contracts\BannerDriver;

class Module implements ModuleContract
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
    public function driver(): BannerDriver
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
     * @param param type $param
     * @return data type
     */
    public function all()
    {
        return $this->driver()->all();
    }

    /**
     * Please describe process of this method.
     *
     * @param param type $param
     * @return data type
     */
    public function search($request = null)
    {
        return $this->driver()->search($request);
    }

    /**
     * Please describe process of this method.
     *
     * @param param type $param
     * @return data type
     */
    public function find(string $param)
    {
        return $this->driver()->find($param);
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
