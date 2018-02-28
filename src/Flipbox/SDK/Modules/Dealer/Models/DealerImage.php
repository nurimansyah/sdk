<?php

namespace Flipbox\SDK\Modules\Dealer\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class DealerImage extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $visible = [
        'filename',
        'image',
    ];

    /**
     * {@inheritdoc}
     */
    protected $appends = [
        'image',
    ];

    /**
     * {@inheritdoc}
     */
    public function getConnectionName(): string
    {
        return config('flipbox-sdk.modules.dealer.drivers.eloquent.connection', 'cms');
    }

    /**
     * Get image attribute.
     *
     * @return string
     */
    protected function getImageAttribute(): string
    {
        return URL::cms($this->attributes['storage_path']);
    }
}
