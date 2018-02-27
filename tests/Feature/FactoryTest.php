<?php

namespace Feature\Tests;

use Tests\TestCase;
use Flipbox\SDK\Factory;
use Flipbox\SDK\Facades\SDK;

class FactoryTest extends TestCase
{
    public function testFactoryCanBeResolvedByApplicationContainer()
    {
        $this->assertInstanceOf(
            Factory::class,
            $this->app->make('flipbox.sdk')
        );

        $this->assertInstanceOf(
            Factory::class,
            $this->app->make(Factory::class)
        );

        $this->assertInstanceOf(
            Factory::class,
            sdk()
        );
    }
}
