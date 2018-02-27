<?php

namespace Feature\Translation\Tests;

use Tests\TestCase;
use Flipbox\SDK\Factory;
use Flipbox\SDK\Facades\SDK;
use Flipbox\SDK\Modules\Translation\Module;
use Flipbox\SDK\Modules\Translation\Drivers\File;
use Flipbox\SDK\Modules\Translation\Drivers\Eloquent;
use Flipbox\SDK\Modules\Translation\Contracts\TranslationDriver;
use Flipbox\SDK\Modules\Translation\Module as TranslationModule;

class TranslationTest extends TestCase
{
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

    public function testDefaultTranslationDriverIsEloquent()
    {
        $module = $this->bootEloquentDriver();

        $this->assertInstanceOf(
            TranslationDriver::class,
            $module->driver()
        );

        $this->assertInstanceOf(
            Eloquent::class,
            $module->driver()
        );
    }

    public function testUsingFileDriverIfTranslationPathConfigurationIsPresent()
    {
        $module = $this->bootFileDriver(env('FLIPBOX_CMS_TRANSLATION_PATH'));

        $this->assertInstanceOf(
            TranslationDriver::class,
            $module->driver()
        );

        $this->assertInstanceOf(
            File::class,
            $module->driver()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFileDriverThrowsExceptionWhenTranslationPathIsNotReadable()
    {
        $module = $this->bootFileDriver('/somewhere/i/belong');

        $module->driver();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEloquentDriverThrowsExceptionWhenConnectionConfigurationIsNotPresent()
    {
        $this->app->config->set('flipbox-sdk.modules.translation.drivers.eloquent.connection', 'undefined');

        $module = $this->bootEloquentDriver();

        $module->translate('anu.gemes', 'Anu Gemes', 'en');
    }

    public function testBasicTranslationUsingEloquentDriver()
    {
        $module = $this->bootEloquentDriver();

        $this->checkTranslation($module);
        $this->checkTranslationUsingSession($module);
        $this->checkTranslationUsingHelperWithoutSession();
        $this->checkTranslationUsingHelperWithSession();
    }

    public function testBasicTranslationUsingFileDriver()
    {
        $module = $this->bootFileDriver(env('FLIPBOX_CMS_TRANSLATION_PATH'));

        $this->checkTranslation($module);
        $this->checkTranslationUsingSession($module);
        $this->checkTranslationUsingHelperWithoutSession();
        $this->checkTranslationUsingHelperWithSession();
    }

    public function testEloquentDriverCache()
    {
        $module = $this->bootEloquentDriver();

        $this->checkTranslationCache($module, 'en', 'validation.accepted');
    }

    public function testFileDriverCache()
    {
        $module = $this->bootFileDriver();

        $this->checkTranslationCache($module, 'en', 'validation.accepted');
    }

    public function testFileDriverMayReturnCollection()
    {
        $module = $this->bootFileDriver(env('FLIPBOX_CMS_TRANSLATION_PATH'));

        $this->assertTrue(
            is_array(
                $module->translate('validation.between', [], 'en')
            )
        );
    }

    public function testEloquentDriverMayReturnCollection()
    {
        $module = $this->bootEloquentDriver();

        $this->assertTrue(
            is_array(
                $module->translate('validation.between', [], 'en')
            )
        );
    }

    public function testTranslationConsitencyBetweenDriver()
    {
        $file = $this->bootFileDriver(env('FLIPBOX_CMS_TRANSLATION_PATH'));
        $eloquent = $this->bootEloquentDriver();

        $this->assertEquals(
            $file->translate('validation.between', [], 'en'),
            $eloquent->translate('validation.between', [], 'en')
        );

        foreach ($this->getExpectations()['en'] as $key => $result) {
            $default = '';

            if (is_array($result)) {
                ['result' => $result, 'default' => $default] = $result;
            }

            $this->assertEquals(
                $file->translate($key, $default, 'en'),
                $eloquent->translate($key, $default, 'en')
            );
        }
    }

    protected function checkTranslationModule($translationModule)
    {
        $this->assertInstanceOf(
            TranslationModule::class,
            $translationModule
        );
    }

    protected function bootEloquentDriver(): Module
    {
        return $this->bootFileDriver(null);
    }

    protected function bootFileDriver(?string $translationPath = null): Module
    {
        $this->app->config->set('flipbox-sdk.modules.translation.drivers.file.path', $translationPath);

        return $this->app->make(Factory::class)
            ->resolve('translation')
            ->clear();
    }

    protected function getExpectations()
    {
        return require __DIR__.'/../../expectations/translation.expectation.php';
    }

    protected function checkTranslation(Module $module)
    {
        foreach ($this->getExpectations() as $locale => $pairs) {
            foreach ($pairs as $key => $result) {
                $default = '';

                if (is_array($result)) {
                    ['result' => $result, 'default' => $default] = $result;
                }

                $this->assertEquals(
                    $module->translate($key, $default, $locale),
                    $result
                );
            }
        }
    }

    protected function checkTranslationUsingSession(Module $module)
    {
        foreach ($this->getExpectations() as $locale => $pairs) {
            $this->app->make('session')->put(
                $this->app->config->get('flipbox-sdk.locale.session'),
                $locale
            );

            foreach ($pairs as $key => $result) {
                $default = '';

                if (is_array($result)) {
                    ['result' => $result, 'default' => $default] = $result;
                }

                $this->assertEquals(
                    $module->translate($key, $default),
                    $result
                );
            }
        }
    }

    protected function checkTranslationUsingHelperWithoutSession()
    {
        foreach ($this->getExpectations() as $locale => $pairs) {
            foreach ($pairs as $key => $result) {
                $default = '';

                if (is_array($result)) {
                    ['result' => $result, 'default' => $default] = $result;
                }

                $this->assertEquals(
                    translate($key, $default, $locale),
                    $result
                );
            }
        }
    }

    protected function checkTranslationUsingHelperWithSession()
    {
        foreach ($this->getExpectations() as $locale => $pairs) {
            $this->app->make('session')->put(
                $this->app->config->get('flipbox-sdk.locale.session'),
                $locale
            );

            foreach ($pairs as $key => $result) {
                $default = '';

                if (is_array($result)) {
                    ['result' => $result, 'default' => $default] = $result;
                }

                $this->assertEquals(
                    translate($key, $default),
                    $result
                );
            }
        }
    }

    protected function checkTranslationCache(Module $module, string $locale, string $key)
    {
        $driver = $module->flush()->driver();
        $result = $driver->translate($key, $locale);
        $cache = Eloquent::$translated;

        $this->assertTrue(is_array($cache));

        $this->assertCount(1, $cache);

        $this->assertArrayHasKey("{$locale}.${key}", $cache);

        $this->assertEquals(
            $cache["{$locale}.${key}"],
            $result
        );
    }
}
