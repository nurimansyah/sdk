<?php

namespace Feature\Banner\Tests;

use Tests\TestCase;
use Flipbox\SDK\Factory;
use Flipbox\SDK\Facades\SDK;
use Flipbox\SDK\Modules\Banner\Module;
use Flipbox\SDK\Modules\Banner\Drivers\Eloquent;
use Flipbox\SDK\Modules\Banner\Contracts\BannerDriver;

class BannerTest extends TestCase
{
    public function testFactoryCanCreateModuleUsingFacades()
    {
        $this->checkModule(
            SDK::banner()
        );

        $this->checkModule(
            SDK::resolve('banner')
        );

        $this->checkModule(
            sdk('banner')
        );
    }

    public function testFactoryCanCreateModuleUsingMethodAccessor()
    {
        $module = $this->app->make(Factory::class)->banner();

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingArrayAccess()
    {
        $module = $this->app->make(Factory::class)['banner'];

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingObjectAccessor()
    {
        $module = $this->app->make(Factory::class)->banner;

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingHelper()
    {
        $module = sdk('banner');

        $this->checkModule($module);
    }

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

    protected function bootDriver(): Module
    {
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
        $expectations = $this->getExpectations();

        $this->assertEquals(
            $module->all(),
            $expectations
        );

        $this->assertEquals(
            $module->get(1),
            collect($expectations)->where('id', 1)->first()
        );

        $this->assertEquals(
            $module->get(2),
            collect($expectations)->where('id', 2)->first()
        );
    }

    protected function checkModule($module)
    {
        $this->assertInstanceOf(Module::class, $module);
    }
}
