<?php

namespace Flipbox\SDK\Modules\Menu\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuContent extends Model
{
    use SoftDeletes;

    /**
     * {@inheritdoc}
     */
    protected $visible = [
        'label',
        'type',
        'url',
        'icon',
        'children',
    ];

    /**
     * {@inheritdoc}
     */
    protected $appends = [
        'icon',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'lang');
    }

    /**
     * May has many children.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'menu_id')
            ->where('active', true)
            ->orderBy('sort');
    }

    /**
     * Get active menu content that has no parent (it's root!).
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $locale
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveRoot(Builder $builder, string $locale): Builder
    {
        return $builder->whereNull('parent_id')
            ->where('active', true)
            ->whereHas('language', function ($query) use ($locale) {
                return $query->where('key', $locale);
            });
    }

    /**
     * Get icon attribute (computed).
     *
     * @return string|null
     */
    protected function getIconAttribute(): ?string
    {
        return $this->featured_image;
    }

    /**
     * Get featured image attribute.
     *
     * @param string|null $value
     *
     * @return string|null
     */
    protected function getFeaturedImageAttribute(?string $value): ?string
    {
        return is_null($value) ? null : URL::cms('/storage/images/'.$value);
    }
}
