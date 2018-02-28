<?php

namespace Feature\Dealer\Tests;

use Tests\TestCase;
use Flipbox\SDK\Factory;
use Flipbox\SDK\Facades\SDK;
use Flipbox\SDK\Modules\Dealer\Module;
use Flipbox\SDK\Modules\Dealer\Drivers\Eloquent;
use Flipbox\SDK\Modules\Dealer\Contracts\DealerDriver;

class DealerTest extends TestCase
{
    public function testFactoryCanCreateModuleUsingFacades()
    {
        $this->checkModule(
            SDK::dealer()
        );

        $this->checkModule(
            SDK::resolve('dealer')
        );

        $this->checkModule(
            sdk('dealer')
        );
    }

    public function testFactoryCanCreateModuleUsingMethodAccessor()
    {
        $module = $this->app->make(Factory::class)->dealer();

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingArrayAccess()
    {
        $module = $this->app->make(Factory::class)['dealer'];

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingObjectAccessor()
    {
        $module = $this->app->make(Factory::class)->dealer;

        $this->checkModule($module);
    }

    public function testFactoryCanCreateModuleUsingHelper()
    {
        $module = sdk('dealer');

        $this->checkModule($module);
    }

    public function testDriver()
    {
        $module = $this->bootEloquentDriver();

        $this->assertInstanceOf(
            DealerDriver::class,
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
        $all = $module->all();
        $expectations = $this->getExpectations();

        $this->assertTrue(is_array($all));
        $this->assertEquals($all, $expectations);

        foreach ($expectations as $expectation) {
            $this->assertEquals($module->get($expectation['id']), $expectation);

            $this->assertEquals($module->search(
                ['city' => $expectation['city']]
            ), [$expectation]);

            $this->assertEquals($module->search(
                [
                    ['city', 'LIKE', $expectation['city']]
                ]
            ), [$expectation]);
        }
    }

    /**
     * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function testNotFound()
    {
        $module = $this->bootEloquentDriver();

        $module->get(404);
    }

    protected function checkModule($module)
    {
        $this->assertInstanceOf(
            Module::class,
            $module
        );
    }

    protected function bootEloquentDriver(): Module
    {
        config()->set('flipbox-sdk.modules.dealer.drivers.eloquent.connection', 'cms');

        return $this->bootDriver();
    }

    protected function bootDriver(): Module
    {
        return $this->app->make(Factory::class)
            ->resolve('dealer')
            ->clear();
    }

    protected function getExpectations()
    {
        return require __DIR__.'/../../expectations/dealer.expectation.php';
    }
}
