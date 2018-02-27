<?php

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
];
