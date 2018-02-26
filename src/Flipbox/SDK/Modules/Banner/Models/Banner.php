<?php

namespace Flipbox\SDK\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * One to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bannerContents()
    {
        return $this->hasMany(BannerContent::class);
    }
}
