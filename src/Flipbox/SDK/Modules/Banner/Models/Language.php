<?php

namespace Flipbox\SDK\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * setConnection driver.
     *
     * @var array
     */
    public function getConnectionName()
    {
        return config('flipbox-sdk.modules.banner.drivers.eloquent.connection', 'cms');
    }

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
