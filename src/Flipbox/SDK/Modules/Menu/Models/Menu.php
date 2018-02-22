<?php

namespace Flipbox\SDK\Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
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
     * One to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menuContents()
    {
        return $this->hasMany(MenuContent::class);
    }

    /**
     * One to Many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id')->orderBy('sort');
    }

    /**
     * Make tree Menu.
     *
     * @return data type
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('sort');
    }
}
