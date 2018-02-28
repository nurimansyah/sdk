<?php

namespace Flipbox\SDK\Modules\Dealer\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    /**
     * One to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function facilityContents()
    {
        return $this->hasMany(FacilityContent::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionName(): string
    {
        return config('flipbox-sdk.modules.dealer.drivers.eloquent.connection', 'cms');
    }
}
