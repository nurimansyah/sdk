<?php

return [
    [
        'label' => 'Foo',
        'type' => 'internal_link',
        'url' => '/foo',
        'icon' => config('flipbox-sdk.url').'/storage/images/img-menu/K0Dx2VOQhwfFP1AlYgTDbxQnJtWN87u6Ag.png',
        'children' => [
            [
                'label' => 'Bar',
                'type' => 'internal_link',
                'url' => '/bar',
                'icon' => null,
                'children' => [
                    [
                        'label' => 'Quux',
                        'type' => 'external_link',
                        'url' => '/quux',
                        'icon' => null,

                        'children' => [],
                    ],
                    [
                        'label' => 'Baz',
                        'type' => 'internal_link',
                        'url' => '/baz',
                        'icon' => null,

                        'children' => [],
                    ],
                ],
            ],
        ],
    ],
];
