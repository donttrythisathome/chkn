<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Account account
 * @property int DirectumID
 * @property int IsCheckedOut
 * @property int ID
 * @property float Longitude
 * @property float Latitude
 * @property string Date
 */
class Location extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class,'DirectumID','id');
    }
}
