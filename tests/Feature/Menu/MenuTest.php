<?php

namespace Feature\Menu\Tests;

use Tests\TestCase;
use Flipbox\SDK\Factory;
use Flipbox\SDK\Facades\SDK;
use Flipbox\SDK\Modules\Menu\Drivers\Eloquent;
use Flipbox\SDK\Modules\Menu\Contracts\MenuDriver;
use Flipbox\SDK\Modules\Menu\Module as MenuModule;

class MenuTest extends TestCase
{
    public function testFactoryCanCreateModuleUsingFacades()
    {
        $this->checkModule(
            SDK::menu()
        );

        $this->checkModule(
            SDK::resolve('menu')
        );

        $this->checkModule(
            sdk('menu')
        );
    }

    public function testFactoryCanCreateModuleUsingMethodAccessor()
    {
        $module = $this->app->make(Factory::class)->menu();

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingArrayAccess()
    {
        $module = $this->app->make(Factory::class)['menu'];

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingObjectAccessor()
    {
        $module = $this->app->make(Factory::class)->menu;

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingHelper()
    {
        $module = sdk('menu');

        $this->checkModule($module);
    }

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

    public function testBasic()
    {
        $module = $this->bootEloquentDriver();
        $menus = $module->all();

        $this->assertTrue(is_array($menus));

        $this->assertEquals($menus, $this->getExpectations());
    }

    protected function checkModule($module)
    {
        $this->assertInstanceOf(
            MenuModule::class,
            $module
        );
    }

    protected function bootEloquentDriver(): MenuModule
    {
        return $this->bootDriver();
    }

    protected function bootDriver(): MenuModule
    {
        return $this->app->make(Factory::class)
            ->resolve('menu')
            ->clear();
    }

    protected function getExpectations()
    {
        return require __DIR__.'/../../expectations/menu.expectation.php';
    }
}
