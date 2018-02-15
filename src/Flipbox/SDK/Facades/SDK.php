<?php

namespace Flipbox\SDK\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string version()
 *
 * @see \Flipbox\SDK\Factory
 */
class SDK extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'flipbox.sdk';
    }
}
