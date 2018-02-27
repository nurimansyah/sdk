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

    /**
     * Get banner.
     *
     * @param int    $id
     * @param string $locale
     *
     * @return array
     */
    public function get(int $id, string $locale = ''): array;

    /**
     * Search banner.
     *
     * @param array  $criteria
     * @param string $locale
     *
     * @return array
     */
    public function search(array $criteria, string $locale = ''): array;
}
