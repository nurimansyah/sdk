<?php

namespace Feature\Tests;

use Tests\TestCase;
use Flipbox\SDK\Factory;
use Flipbox\SDK\Facades\SDK;
use Flipbox\SDK\Modules\Translation\Module as TranslationModule;

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

    public function testFactoryCanCreateModuleUsingFacades()
    {
        $this->checkTranslationModule(
            SDK::translation()
        );

        $this->checkTranslationModule(
            SDK::resolve('translation')
        );

        $this->checkTranslationModule(
            sdk('translation')
        );
    }

    public function testFactoryCanCreateModuleUsingMethodAccessor()
    {
        $translationModule = $this->app->make(Factory::class)->translation();

        $this->checkTranslationModule($translationModule);
    }

    public function testFactoryCanCreateModuleUsingArrayAccess()
    {
        $translationModule = $this->app->make(Factory::class)['translation'];

        $this->checkTranslationModule($translationModule);
    }

    public function testFactoryCanCreateModuleUsingObjectAccessor()
    {
        $translationModule = $this->app->make(Factory::class)->translation;

        $this->checkTranslationModule($translationModule);
    }

    public function testFactoryCanCreateModuleUsingHelper()
    {
        $translationModule = sdk('translation');

        $this->checkTranslationModule($translationModule);
    }

    protected function checkTranslationModule($translationModule)
    {
        $this->assertInstanceOf(
            TranslationModule::class,
            $translationModule
        );
    }
}
