<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property string address
 * @property float lat
 * @property float lat_near
 * @property float lng
 * @property float lng_near
 * @property string created_at
 * @property string updated_at
 */
class Account extends Model
{
    /** @var string */
    protected $table = 'accounts';

    /** @var array */
    protected $guarded = [];

    /** @var string[] */
    protected $casts = [
        'lat' => 'double',
        'lng' => 'double'
    ];

    /**
     * @return float|null
     */
    protected function getLatNearAttribute()
    {
        if (empty($lat = (string)$this->getAttribute('lat'))) {
            return null;
        }
        $lat{9} = rand(0, 9);
        $lat{10} = rand(0, 9);

        return (float)$lat;
    }

    /**
     * @return float|null
     */
    protected function getLngNearAttribute()
    {
        if (empty($lng = (string)$this->getAttribute('lng'))) {
            return null;
        }
        $lng{9} = rand(0, 9);
        $lng{10} = rand(0, 9);

        return (float)$lng;
    }
}
