<?php

namespace Flipbox\SDK\Modules\Dealer\Models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $visible = [
        'name',
        'display_name',
    ];

    /**
     * {@inheritdoc}
     */
    public function getConnectionName(): string
    {
        return config('flipbox-sdk.modules.dealer.drivers.eloquent.connection', 'cms');
    }
}
