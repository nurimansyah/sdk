<?php

namespace Flipbox\SDK\Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * {@inheritdoc}
     */
    public function getConnectionName()
    {
        return config('flipbox-sdk.modules.menu.drivers.eloquent.connection', 'cms');
    }
}
