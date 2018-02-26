<?php

namespace Flipbox\SDK\Modules\Banner\Contracts;

interface BannerDriver
{
    /**
     * fetch All Banner
     *
     * @return string|array
     */
    public function all();

    /**
     * search All Banner
     *
     * @param string|array $request
     *
     * @return string|array
     */
    public function search($request = null);

    /**
     * search All Banner
     *
     * @param string|array $request
     *
     * @return string|array
     */
    public function find(string $param);
}
