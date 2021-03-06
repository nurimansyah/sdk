<?php

namespace Flipbox\SDK\Providers;

use Flipbox\SDK\Factory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SDKServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../config/flipbox-sdk.php' => config_path('flipbox-sdk.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../../config/flipbox-sdk.php', 'flipbox-sdk'
        );

        Blade::directive('translate', function ($expression): string {
            [$key, $default, $locale] = array_merge(
                array_map(function (string $string): string {
                    return trim($string);
                }, explode(',', $expression, 3)),
                ['""', '""', '""']
            );

            return "<?php echo e(translate($key, $default, $locale)); ?>";
        });

        URL::macro('cms', function (string $path): string {
            return config('flipbox-sdk.url').'/'.ltrim($path, '/');
        });
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new Factory();
        });

        $this->app->alias(Factory::class, 'flipbox.sdk');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Factory::class,
            'flipbox.sdk',
        ];
    }
}
