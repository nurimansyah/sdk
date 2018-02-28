<?php

namespace Flipbox\SDK\Modules\Dealer\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * {@inheritdoc}
     */
    public function getConnectionName(): string
    {
        return config('flipbox-sdk.modules.dealer.drivers.eloquent.connection', 'cms');
    }
}
