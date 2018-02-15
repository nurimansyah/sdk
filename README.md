# FLIPBOX CMS SDK PACKAGE

This repo contains Flipbox CMS SDK package.
If you build website using Flipbox CMS, then you may need an elegant solution to integrate your datas in CMS to your website,
**especially if you develop your website and the CMS in a different project**.
You can use this package anywhere, as long as you are using Laravel Framework.
But it's limited to Flipbox CMS data service only.
Means if you use Wordpress CMS, you can't use this package.

### INSTALLATION

> YOU NEED TO INSTALL FLIPBOX CMS FIRST. WITHOUT IT, THIS PACKAGE IS **USELESS**. CONSULT TO DEVELOPER TO KNOW HOW TO MAKE THIS HAPPEN.

Install this package thru composer:

```sh
composer req "flipboxstudio/sdk:~0.0.1"
```

If you use Laravel >=5.5, then you don't need to configure anything.
The service provider and facade will [auto discover by itself](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518).
But if you use Laravel <5.5, you need to register this provider in your `config/app.php`:

```php
<?php

return [
    ...

    'providers' => [
        // ...

        /*
         * Package Service Providers...
         */
        Flipbox\SDK\Providers\SDKServiceProvider::class,

        // ...
    ],

    ...
];
```

### CONFIGURATION

First, you need to publish the basic configuration using below command:

```sh
php artisan vendor:publish --provider="Flipbox\SDK\Providers\SDKServiceProvider" --tag="config"
```

Here's the basic configuration looks like:

```php
return [
    'modules' => [
        'translation' => [
            // Session name
            'session' => 'locale',

            // Driver configuration
            'drivers' => [
                // Eloquent driver configuation
                'eloquent' => [
                    // Database connection that eloquent should use
                    'connection' => env('FLIPBOX_CMS_CONNECTION', 'cms'),
                ],

                'file' => [
                    'path' => env('FLIPBOX_CMS_TRANSLATION_PATH'),
                ],
            ],
        ],
    ],
];
```

I'll explain this configuration later.

### TRANSLATION MODULE

Flipbox SDK comes with seamless integration with translation feature.
You can translate any string using CMS data without hassle.
First thing first, you need to configure the `translation` driver configuration. There are two drivers by default, `eloquent` and `file`. The `eloquent` meant to be used in development environment only, since it will execute so much query (even it's cached on the runtime, still it's not the best approach when we want to use `eloquent` driver in production environment). If you want to use eloquent driver, just simply set `drivers.file.path` to `null`. Something like this:

```php
'drivers' => [
    // ...

    'file' => [
        'path' => null,
    ],
]
```

If `drivers.file.path` configuration is present (means it's not `null` or an empty string), the `file` driver will be used. To configure `eloquent` connection, you have to create a new `connection` configuration in your `config/database.php` file. As it said, by default it will use `cms` connection, so you `database` configuration should be like this:

```php
return [
    // ...

    'connections' => [
        'cms' => [
            'driver' => 'mysql',
            'host' => YOUR_CMS_DATABASE_HOST,
            'port' => YOUR_CMS_DATABASE_PORT,
            'database' => YOUR_CMS_DATABASE_NAME,
            'username' => YOUR_CMS_DATABASE_USERNAME,
            'password' => YOUR_CMS_DATABASE_PASSWORD,
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
    ]
];
```

To use another connection name, just change the `drivers.eloquent.connection` value to something. For example:

```php
'drivers' => [
    'eloquent' => [
        'connection' => 'foo',
    ],

    // ...
]
```

Then adjust your `database` configuration:

```php
return [
    // ...

    'connections' => [
        'foo' => [
            'driver' => 'mysql',
            'host' => YOUR_CMS_DATABASE_HOST,
            'port' => YOUR_CMS_DATABASE_PORT,
            'database' => YOUR_CMS_DATABASE_NAME,
            'username' => YOUR_CMS_DATABASE_USERNAME,
            'password' => YOUR_CMS_DATABASE_PASSWORD,
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
    ]
];
```

When building to production, you may need to setup the `file` driver:

```php
'drivers' => [
    // ...

    'file' => [
        'path' => '/absoulute/path/to/cms/resources/lang',
    ],
]
```

Notice the `resources/lang` is needed. It's a lang path in Laravel project.

#### TRANSLATE A STRING

There's so many way to translate a string, but the simplest way is using `translate` function:

```php
function translate(string $key, $default = null, string $locale = '');
```

- `$key` is a translation key.
- `$default` is a default value when translation is not found.
- `$locale` is the locale you want to translate your string.

When you ommit `$locale` argument, then the package will try to guess your locale via [session](https://laravel.com/docs/5.5/session). The default session name used to guess your current locale is `locale`. You can change this session name by changing your configuration to:

```php
return [
    'modules' => [
        'translation' => [
            // Session name
            'session' => 'custom-locale-session-name',

            // ...
        ],
    ],
];
```

If the session value return `null` or empty string, then the package will use you default locale in your configuration (`config/app.php`).

```php
return [
    // ...

    'locale' => 'id',

    // If `locale` is not set, then `fallback_locale` will be used.
    'fallback_locale' => 'en',

    // ...
];
```

So the summary of guessing your locale would be like this (ordered by priority):

1. Session
2. `app.locale` configuration
3. `app.fallback_locale` configuration

Usage:

```php
translate('auth.failed', 'Authentication failed');
translate('auth.failed', 'Authentication failed', 'en');
translate('auth.failed', 'Otentikasi gagal', 'id');
```

Usage in Blade file using custom directive:

```
@translate(auth.failed, Authentication failed)
```

When using custom directive, you **SHOULD NEVER** place a quote/double quote between arguments.

```
<!-- WRONG !!! -->
@translate('auth.failed', 'Authentication failed')
<!-- ALSO WRONG !!! -->
@translate("auth.failed", "Authentication failed")
```

Also you CANNOT pass the third argument, the `locale`, when using custom directive.
If you want to pass `locale` argument, use `translate` method.

```
<!-- LOCALE ARGUMENT IS NOT ACCEPTED !!! -->
@translate(auth.failed, Authentication failed, en)
```

In addition, when using custom directive, any HTML tag will be encoded through PHP `htmlspecialchars` function.
If you want to unescape the translation content, you should use `translate` function followed by unescaped Blade render data method:

```
{!! translate('auth.failed', '<strong>Authentication failed</strong>') !!}
```

### CONTRIBUTING

`-- TO BE DISCUSSED --`

### LICENSE

Copyright 2018 Flipbox

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.