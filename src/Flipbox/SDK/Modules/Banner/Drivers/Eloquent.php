<?php

namespace Flipbox\SDK\Modules\Banner\Drivers;

use Flipbox\SDK\Modules\Banner\Models\Banner;
use Flipbox\SDK\Modules\Banner\Models\BannerContent;
use Flipbox\SDK\Modules\Banner\Contracts\BannerDriver;

class Eloquent implements BannerDriver
{
    /**
     * Fetch all banners.
     *
     * @param string $locale
     *
     * @return array
     */
    public function all(string $locale = ''): array
    {
        $collection = BannerContent::where('active', true)
            ->whereHas('banner', function ($query) {
                $query->whereRaw('? BETWEEN `start_date` AND `end_date`', [date('Y-m-d')]);
            })
            ->whereHas('language', function ($query) use ($locale) {
                $query->where('key', $locale);
            })
            ->get()
            ->sortBy('banner.sort');

        return array_values($collection->toArray());
    }

    /**
     * Get banner specified by id and locale.
     *
     * @param integer $id
     * @param string $locale
     * @return array
     */
    public function get(int $id, string $locale = ''): array
    {
        $collection = BannerContent::with(['banner'])
            ->where('banner_id', $id)
            ->whereHas('language', function ($query) use ($locale) {
                $query->where('key', $locale);
            })
            ->firstOrFail();

        return $collection->toArray();
    }
}
