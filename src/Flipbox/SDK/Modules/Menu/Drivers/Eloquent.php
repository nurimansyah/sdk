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
        $collection = MenuContent::with(['children' => function($query) {
            $query->with(['children' => function($q) {
                return $q->with('children');
            }]);
        }])
        ->whereNull('parent_id')
        ->whereHas('language', function ($query) {
            return $query->where('key', 'id');
        })
        ->get();
        
        return $collection->toArray();
    }

    /**
     * Get all menu collection.
     *
     * @param param type $param
     * @return array collection
     */
    public function search($param = [])
    {
        $collection = MenuContent::with(['children' => function($query) {
            $query->with(['children' => function($q) {
                return $q->with('children');
            }]);
        }])
        ->whereNull('parent_id')
        ->whereHas('language', function ($query) use ($param) {
            if ($param['lang']) {
                $query->where('key', $param['lang']);
            } else {
                $query->where('key', 'id');
            }

            if ($param['search']) {
                $query->where('menu_contents.label', 'like', "%{$param['search']}%");
            }
            return $query;

        })->get();
        
        return $collection->toArray();
    }
}
