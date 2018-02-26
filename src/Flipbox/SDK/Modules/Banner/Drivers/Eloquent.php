<?php

namespace Flipbox\SDK\Modules\Banner\Drivers;

use Flipbox\SDK\Modules\Banner\Contracts\BannerDriver;
use Flipbox\SDK\Modules\Banner\Models\Banner;
use Flipbox\SDK\Modules\Banner\Models\BannerContent;

class Eloquent implements BannerDriver
{
    /**
     * Please describe process of this method.
     *
     * @param param type $param
     * @return data type
     */
    public function all()
    {
        $collection = BannerContent::whereHas('language', function ($query) {
            return $query->where('key', 'id');
        })
        ->get()->toArray();
        
        return $collection;
    }

    /**
     * Get all menu collection.
     *
     * @param param type $param
     * @return array collection
     */
    public function search($param = [])
    {
        $collection = BannerContent::whereHas('language', function ($query) use ($param) {
            if ($param['lang']) {
                $query->where('key', $param['lang']);
            } else {
                $query->where('key', 'id');
            }

            if ($param['search']) {
                $query->where('banner_contents.title', 'like', "%{$param['search']}%");
            }
            return $query;

        })->get();
        
        return $collection->toArray();
    }

    /**
     * Get banner by param.
     *
     * @param  string  $param
     * @return array
     */
    public function find(string $param)
    {
        $collection = BannerContent::where('btn_url', $param)->get()->toArray();

        if (count($collection) < 1) {
            $collection = [
                'error' => [
                    'code' => 404,
                    'message' => 'Oops'
                ] 
            ];
        }
        
        return $collection;
    }


}
