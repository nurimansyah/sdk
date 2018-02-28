<?php

namespace Flipbox\SDK\Modules\Dealer\Contracts;

interface DealerDriver
{
    /**
     * Fetch all dealer.
     *
     * @return array
     */
    public function all(string $locale = null): array;

    /**
     * Get a dealer by id.
     *
     * @param int    $id
     * @param string $locale
     *
     * @return array
     */
    public function get(int $id, string $locale = null): array;

    /**
     * Search dealer.
     *
     * @param array  $criteria
     * @param string $locale
     *
     * @return array
     */
    public function search(array $criteria, string $locale = null): array;
}
