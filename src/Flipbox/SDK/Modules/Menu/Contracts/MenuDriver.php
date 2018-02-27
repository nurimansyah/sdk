<?php

namespace Flipbox\SDK\Modules\Menu\Contracts;

interface MenuDriver
{
    /**
     * fetch All Menu.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all(string $locale = null);
}
