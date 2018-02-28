<?php

namespace Flipbox\SDK\Modules\Dealer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dealer extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'sort',
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * May has many services.
     *
     * @return BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * May has many car types.
     *
     * @return BelongsToMany
     */
    public function carTypes(): BelongsToMany
    {
        return $this->belongsToMany(CarType::class);
    }

    /**
     * May has many facilities.
     *
     * @return BelongsToMany
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class);
    }

    /**
     * May has many images.
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(DealerImage::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionName(): string
    {
        return config('flipbox-sdk.modules.dealer.drivers.eloquent.connection', 'cms');
    }

    public function scopeLocale(Builder $builder, string $locale): Builder
    {
        return $builder->with([
            'images',
            'services',
            'carTypes',
            'facilities' => function ($query) use ($locale) {
                $query->with(['facilityContents' => function ($query) use ($locale) {
                    $query->whereHas('language', function ($query) use ($locale) {
                        $query->where('key', $locale);
                    });
                }]);
            },
        ])
        ->orderBy('sort');
    }
}
