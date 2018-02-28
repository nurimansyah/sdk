<?php

namespace Flipbox\SDK\Modules\Dealer\Drivers;

use Flipbox\SDK\Modules\Dealer\Models\Dealer;
use Flipbox\SDK\Modules\Dealer\Contracts\DealerDriver;

class Eloquent implements DealerDriver
{
    /**
     * {@inheritdoc}
     */
    public function all(string $locale = null): array
    {
        $dealers = Dealer::locale($locale)
            ->get()
            ->toArray();

        return collect($dealers)->map(function (array $dealer): array {
            return $this->mapResult($dealer);
        })->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id, string $locale = null): array
    {
        $dealer = Dealer::locale($locale)
            ->findOrFail($id)
            ->toArray();

        return $this->mapResult($dealer);
    }

    /**
     * {@inheritdoc}
     */
    public function search(array $criteria, string $locale = null): array
    {
        $dealers = Dealer::locale($locale)->where($criteria)
            ->get()
            ->toArray();

        return collect($dealers)->map(function (array $dealer): array {
            return $this->mapResult($dealer);
        })->toArray();
    }

    /**
     * Map dealer result.
     *
     * @param array $dealer
     *
     * @return array
     */
    protected function mapResult(array $dealer): array
    {
        $dealer['facilities'] = collect($dealer['facilities'])
            ->pluck('facility_contents')
            ->flatten(1)
            ->toArray();

        return $dealer;
    }
}
