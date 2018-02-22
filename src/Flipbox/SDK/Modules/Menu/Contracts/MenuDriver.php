<?php

namespace Flipbox\SDK\Modules\Menu\Contracts;

interface MenuDriver
{
    /**
     * fetch All Menu
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all();

    /**
     * search All Menu
     *
     * @param string|array $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search($request = null);
}
