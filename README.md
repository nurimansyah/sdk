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
    // CMS hostname, ommit trailing slash to prevent error!
    'url' => env('FLIPBOX_CMS_URL'),

    'locale' => [
        // Session name
        'session' => 'locale',
    ],

    'modules' => [
        'translation' => [
            // Driver configuration
            'drivers' => [
                // Eloquent driver configuration
                'eloquent' => [
                    // Database connection that eloquent should use
                    'connection' => env('FLIPBOX_CMS_CONNECTION', 'cms'),
                ],

                'file' => [
                    'path' => env('FLIPBOX_CMS_TRANSLATION_PATH'),
                ],
            ],
        ],

        'menu' => [
            // Driver configuration
            'drivers' => [
                // Eloquent driver configuration
                'eloquent' => [
                    // Database connection that eloquent should use
                    'connection' => env('FLIPBOX_CMS_CONNECTION', 'cms'),
                ],
            ],
        ],

        'banner' => [
            // Driver configuration
            'drivers' => [
                // Eloquent driver configuration
                'eloquent' => [
                    // Database connection that eloquent should use
                    'connection' => env('FLIPBOX_CMS_CONNECTION', 'cms'),
                ],
            ],
        ],
    ],
]
```

> **HOLD ON** I'll explain this configuration later.

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
    'locale' => [
        // Session name
        'session' => 'custom-locale-session-name',
    ],

    ...
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
@translate('auth.failed', 'Authentication failed')
@translate('auth.failed', 'Authentication failed', 'en)
@translate('auth.failed', 'Otentikasi gagal', 'id')
```

In addition, when using custom directive, any HTML tag will be encoded through PHP `htmlspecialchars` function.
If you want to unescape the translation content, you should use `translate` function followed by unescaped Blade render data method:

```
{!! translate('auth.failed', '<strong>Authentication failed</strong>') !!}
```

### MENU MODULE

Another module that comes with this SDK is menu module.
You can get all active menu with its children using this function:

```php
$menu = menu(); // guess current locale via session / configuration

// To force using given locale
$menu = menu('en');
$menu = menu('id');
```

Result:
```php
[
    [
        'label' => 'Foo',
        'type' => 'internal_link',
        'url' => '/foo',
        'icon' => 'https://mitsubishi.test/storage/images/img-menu/K0Dx2VOQhwfFP1AlYgTDbxQnJtWN87u6Ag.png',
        'children' => [
            [
                'label' => 'Bar',
                'type' => 'internal_link',
                'url' => '/bar',
                'icon' => null,
                'children' => [
                    [
                        'label' => 'Baz',
                        'type' => 'external_link',
                        'url' => 'https:://baz.com',
                        'icon' => null,
                        'children' => [
                            [
                                'label' => 'Quux',
                                'type' => 'external_link',
                                'url' => 'https:://quux.com',
                                'icon' => null,

                                'children' => [],
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],
]
```

### BANNER MODULE

Another module that comes with this SDK is a banner module.
You can get all active banner with its children using this function:

```php
$banner = banner(); // guess current locale via session / configuration

// To force using given locale
$banner = banner('en');
$banner = banner('id');
```

Result:
```php
[
    [
        'id' => 2,
        'title' => 'xxx',
        'url' => null,
        'btn_url' => 'xxx',
        'featured_image' => 'https://mitsubishi.test/storage/images/banner/ACE1uu7qZFVmP4fhv3Rx1C4yNpFSEcNfDa.jpg',
        'featured_image_mobile' => 'https://mitsubishi.test/storage/images/banner/ptuN8gpfz3oagkRFW2MUefc853EbfoFvwg.jpg',
        'content' => '<p>xxx</p>',
        'meta_title' => 'xxx',
        'meta_description' => 'xxx',
        'start_date' => '2018-02-27',
        'end_date' => '2018-04-09',
    ],
    [
        'id' => 1,
        'title' => 'Title',
        'url' => null,
        'btn_url' => 'Button',
        'featured_image' => 'https://mitsubishi.test/storage/images/banner/ERBHN2bNXHg1ddm73O1Uh5F77162PAfc7p.jpg',
        'featured_image_mobile' => 'https://mitsubishi.test/storage/images/banner/a6nkAl3kcdDLqLBFXSqP6tyv29RyvWT7K1.jpg',
        'content' => '<p>English Content</p>',
        'meta_title' => 'meta',
        'meta_description' => 'Description',
        'start_date' => '2018-02-27',
        'end_date' => '2018-02-28',
    ],
]
```

> **EASY NOW** The banner produced above is already sorted and filtered by given locale. Also, the result is a filtered banner where the date today is in between `start_date` and `end_date`.

Or you can get banner specified by its `ID`, by passing the first argument using integer data type:

```php
$banner = banner(1); // get banner with ID 1, it will guess your current locale

// To force using a specified locale
$banner = banner(1, 'en');
```

Result:
```php
[
    'id' => 2,
    'title' => 'xxx',
    'url' => null,
    'btn_url' => 'xxx',
    'featured_image' => 'https://mitsubishi.test/storage/images/banner/ACE1uu7qZFVmP4fhv3Rx1C4yNpFSEcNfDa.jpg',
    'featured_image_mobile' => 'https://mitsubishi.test/storage/images/banner/ptuN8gpfz3oagkRFW2MUefc853EbfoFvwg.jpg',
    'content' => '<p>xxx</p>',
    'meta_title' => 'xxx',
    'meta_description' => 'xxx',
    'start_date' => '2018-02-27',
    'end_date' => '2018-04-09',
]
```

> **NOTICE** You **MUST** pass an integer value to the first argument if you want to get a specific banner by its ID. If you pass a string, the method will treat your first argument as `locale`.

If you doubt, you can still access the lower level method to avoid type juggling:

```php
// Get banner by its id and locale, you can ommit the second argument to autoguess current locale
$banner = sdk('banner')->get($id, $locale);

// Get all banner with a specific locale, you can ommit the first argument to autoguess current locale
$banner = sdk('banner')->all($locale);
```

### CONTRIBUTING

`-- TO BE DISCUSSED --`

### LICENSE

Copyright 2018 Flipbox

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.