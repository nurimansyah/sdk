<?php

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
