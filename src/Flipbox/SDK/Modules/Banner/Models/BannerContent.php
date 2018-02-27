<?php

namespace Flipbox\SDK\Modules\Banner\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class BannerContent extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $visible = [
        'id',
        'title',
        'url',
        'btn_url',
        'featured_image',
        'featured_image_mobile',
        'content',
        'meta_title',
        'meta_description',
        'start_date',
        'end_date',
    ];

    /**
     * {@inheritdoc}
     */
    protected $appends = [
        'start_date',
        'end_date',
    ];

    /**
     * {@inheritdoc}
     */
    public function getConnectionName()
    {
        return config('flipbox-sdk.modules.menu.drivers.eloquent.connection', 'cms');
    }

    /**
     * Many to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }

    /**
     * Many to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang');
    }

    /**
     * Get id attribute.
     *
     * @return integer
     */
    protected function getIdAttribute(): int
    {
        return $this->banner->getKey();
    }

    /**
     * Get start date attribute.
     *
     * @return string
     */
    protected function getStartDateAttribute(): string
    {
        return $this->banner->start_date;
    }

    /**
     * Get end date attribute.
     *
     * @return string
     */
    protected function getEndDateAttribute(): string
    {
        return $this->banner->end_date;
    }

    /**
     * Get image.
     *
     * @param string $value
     *
     * @return string
     */
    protected function getFeaturedImageAttribute(string $value): string
    {
        return URL::cms('/storage/images/'.$value);
    }

    /**
     * Get image.
     *
     * @param string $value
     *
     * @return string
     */
    protected function getFeaturedImageMobileAttribute(string $value): string
    {
        return URL::cms('/storage/images/'.$value);
    }
}
