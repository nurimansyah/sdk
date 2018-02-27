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
                return $query->where('key', $locale);
            })
            ->get()
            ->sortBy('banner.sort');

        return array_values($collection->toArray());
    }
}
