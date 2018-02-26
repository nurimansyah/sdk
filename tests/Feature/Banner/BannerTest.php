<?php

namespace Feature\Banner\Tests;

use Tests\TestCase;
use Flipbox\SDK\Factory;
use Flipbox\SDK\Modules\Banner\Module;
use Flipbox\SDK\Modules\Banner\Drivers\File;
use Flipbox\SDK\Modules\Banner\Drivers\Eloquent;
use Flipbox\SDK\Modules\Banner\Contracts\BannerDriver;

class BannerTest extends TestCase
{
    public function testDefaultBannerDriverIsEloquent()
    {
        $module = $this->bootEloquentDriver();

        $this->assertInstanceOf(
            BannerDriver::class,
            $module->driver()
        );

        $this->assertInstanceOf(
            Eloquent::class,
            $module->driver()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEloquentDriverThrowsExceptionWhenConnectionConfigurationIsNotPresent()
    {
        $this->app->config->set('flipbox-sdk.modules.banner.drivers.eloquent.connection', 'undefined');

        $module = $this->bootEloquentDriver();

        $module->all();
    }

    public function testBasicBannerUsingEloquentDriver()
    {
        $module = $this->bootEloquentDriver();

        $this->checkBanner($module);
    }

    public function testEloquentDriverMayReturnCollection()
    {
        $module = $this->bootEloquentDriver();

        $this->assertTrue(
            is_array(
                $module->all()
            )
        );
    }

    protected function bootEloquentDriver(): Module
    {
        return $this->bootDriver();
    }

    protected function bootDriver(?string $connection = null): Module
    {
        $this->app->config->set('flipbox-sdk.modules.banner.drivers.eloquent.connection', $connection);

        return $this->app->make(Factory::class)
            ->resolve('banner')
            ->clear();
    }

    protected function getExpectations()
    {
        return require __DIR__.'/../../expectations/banner.expectation.php';
    }

    protected function checkBanner(Module $module)
    {
        foreach ($this->getExpectations() as $pairs) {
            // foreach ($pairs as $key => $result) {
            //     $default = '';

            //     if (is_array($result)) {
            //         ['result' => $result, 'default' => $default] = $result;
            //     }

                
            // }
            
            $this->assertEquals(
                $module->all(),
                $pairs
            );
        }
    }
}
