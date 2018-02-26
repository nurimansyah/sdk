<?php

namespace Feature\Menu\Tests;

use Tests\TestCase;
use Flipbox\SDK\Factory;
use Flipbox\SDK\Modules\Menu\Module;
use Flipbox\SDK\Modules\Menu\Drivers\File;
use Flipbox\SDK\Modules\Menu\Drivers\Eloquent;
use Flipbox\SDK\Modules\Menu\Contracts\MenuDriver;

class MenuTest extends TestCase
{
    public function testDefaultMenuDriverIsEloquent()
    {
        $module = $this->bootEloquentDriver();

        $this->assertInstanceOf(
            MenuDriver::class,
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
        $this->app->config->set('flipbox-sdk.modules.menu.drivers.eloquent.connection', 'undefined');

        $module = $this->bootEloquentDriver();

        $module->all();
    }

    public function testBasicMenuUsingEloquentDriver()
    {
        $module = $this->bootEloquentDriver();

        $this->checkMenu($module);
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
        $this->app->config->set('flipbox-sdk.modules.menu.drivers.eloquent.connection', $connection);

        return $this->app->make(Factory::class)
            ->resolve('menu')
            ->clear();
    }

    protected function getExpectations()
    {
        return require __DIR__.'/../../expectations/menu.expectation.php';
    }

    protected function checkMenu(Module $module)
    {
        foreach ($this->getExpectations() as $pairs) {
            foreach ($pairs as $key => $result) {
                $default = '';

                if (is_array($result)) {
                    ['result' => $result, 'default' => $default] = $result;
                }

                $this->assertEquals(
                    $module->all(),
                    $result
                );
            }
        }
    }
}
