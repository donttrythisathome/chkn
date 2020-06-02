<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Account account
 * @property int directum_id
 * @property int is_checked_out
 * @property int id
 * @property float lat
 * @property float lng
 * @property string created_at
 * @property string updated_at
 */
class Location extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class,'directum_id','id');
    }
}
