<?php

namespace Flipbox\SDK\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * {@inheritdoc}
     */
    public function getConnectionName()
    {
        return config('flipbox-sdk.modules.banner.drivers.eloquent.connection', 'cms');
    }
}
