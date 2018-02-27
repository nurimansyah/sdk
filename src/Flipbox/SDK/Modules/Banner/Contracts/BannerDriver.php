<?php

namespace Flipbox\SDK\Modules\Banner\Contracts;

interface BannerDriver
{
    /**
     * Fetch all banners.
     *
     * @param string $locale
     *
     * @return array
     */
    public function all(string $locale = ''): array;
}
