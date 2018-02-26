<?php

namespace Flipbox\SDK\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;

class BannerContent extends Model
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

    /**
     * One to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Many to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }

    /**
     * Many to Many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang');
    }
}
