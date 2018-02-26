<?php

namespace Flipbox\SDK\Modules\Menu\Drivers;

use Flipbox\SDK\Modules\Menu\Contracts\MenuDriver;
use Flipbox\SDK\Modules\Menu\Models\Menu;
use Flipbox\SDK\Modules\Menu\Models\MenuContent;

class Eloquent implements MenuDriver
{
    /**
     * Please describe process of this method.
     *
     * @param param type $param
     * @return data type
     */
    public function all()
    {
        return Menu::get()->toArray();
    }

    /**
     * Get all menu collection.
     *
     * @param param type $param
     * @return array collection
     */
    public function search($param = [])
    {
        return MenuContent::whereHas('menu')->whereHas('language', function ($query) use ($param) {
            if ($param['lang']) {
                $query->where('key', $param['lang'])->where('label', '!=', null);
            } else {
                $query->where('key', 'id')->where('label', '!=', null);
            }

            if ($param['search']) {
                $query->where('label', 'like', "%{$param['search']}%")
                ->orWhere('url', 'like', "%{$param['search']}%");
            }

            return $query;
        })->get();

    }
}
