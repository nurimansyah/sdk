<?php

namespace Flipbox\SDK\Modules\Menu\Drivers;

use Illuminate\Database\Eloquent\Collection;
use Flipbox\SDK\Modules\Menu\Models\MenuContent;
use Flipbox\SDK\Modules\Menu\Contracts\MenuDriver;

class Eloquent implements MenuDriver
{
    /**
     * Please describe process of this method.
     *
     * @param param string $locale
     *
     * @return array
     */
    public function all(string $locale = null): array
    {
        $collection = MenuContent::activeRoot($locale)->get();

        return $this->collectChildren($collection)->toArray();
    }

    /**
     * Recursively collect all children.
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    protected function collectChildren(Collection $collection): Collection
    {
        foreach ($collection as $index => $menuContent) {
            $menuContent->load(['children' => function ($query) use ($menuContent) {
                return $query->whereHas('language', function ($query) use ($menuContent) {
                    return $query->where('id', $menuContent->lang);
                });
            }]);

            if (0 === $menuContent->children->count()) {
                continue;
            }

            $menuContent->children = $this->collectChildren($menuContent->children);

            $collection[$index] = $menuContent;
        }

        return $collection;
    }
}
