<?php

namespace Flipbox\SDK\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    /**
     * {@inheritdoc}
     */
    public function getConnectionName()
    {
        return config('flipbox-sdk.modules.menu.drivers.eloquent.connection', 'cms');
    }

    /**
     * One to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contents()
    {
        return $this->hasMany(BannerContent::class);
    }
}
