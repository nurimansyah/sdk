<?php

namespace Flipbox\SDK\Modules\Translation\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ltm_translations';

    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return config('flipbox-sdk.modules.translation.drivers.eloquent.connection');
    }
}
