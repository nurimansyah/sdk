<?php

namespace Flipbox\SDK\Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuContent extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * setConnection driver.
     *
     * @var array
     */
    public function getConnectionName()
    {
        return config('flipbox-sdk.modules.menu.drivers.eloquent.connection', 'cms');
    }

    /**
     * Many to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Many to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang');
    }

    /**
     * Many to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function contents()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function getUrlAttribute($value)
    {
        if ($this->attributes['type'] === 'external_link') {
            $data = $value;
        } else {
            $data = url($value);
        }
        return $data;
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = $value;
    }
}
