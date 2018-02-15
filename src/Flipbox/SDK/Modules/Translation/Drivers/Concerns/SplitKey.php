<?php

namespace Flipbox\SDK\Modules\Translation\Drivers\Concerns;

trait SplitKey
{
    /**
     * Split key to locale and group.
     *
     * @param string $key
     *
     * @return array
     */
    protected function splitKey(string $key): array
    {
        return array_merge(
            explode('.', $key, 2),
            ['', '']
        );
    }
}
