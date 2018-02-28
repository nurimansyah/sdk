<?php

namespace Flipbox\SDK\Modules\Dealer\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class FacilityContent extends Model
{
    protected $visible = ['name', 'icon', 'image'];

    /**
     * Many to One relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Many to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang');
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionName(): string
    {
        return config('flipbox-sdk.modules.dealer.drivers.eloquent.connection', 'cms');
    }

    protected function getImageAttribute(?string $value): ?string
    {
        return ($value) ? URL::cms('/storage/images/'.$value) : null;
    }
}
