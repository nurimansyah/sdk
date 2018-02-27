<?php

namespace Flipbox\SDK\Modules\Translation\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'ltm_translations';

    /**
     * {@inheritdoc}
     */
    public function getConnectionName()
    {
        return config('flipbox-sdk.modules.translation.drivers.eloquent.connection', 'cms');
    }
}
