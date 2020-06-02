<?php

namespace App\Facilicom;

use App\Account;
use App\Checkin;
use App\Location;
use Carbon\Carbon;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Collection;

class Service
{
    /** @var Client  */
    protected $client;

    /** @var CacheManager */
    protected $cache;

    /**
     * @param Client $client
     * @param CacheManager $cache
     */
    public function __construct(Client $client, CacheManager $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->cache->remember('fcm_token',1440,function (){
            return $this->client->logon()['Token'];
        });
    }

    public function setLocation(Account $account, int $actionType)
    {
        $this->client->setLocation(
            $this->getToken(),
            $account->getKey(),
            $actionType,
            $account->lat_near,
            $account->lng_near
        );
    }

    /**
     * @param Account $account
     */
    public function checkIn(Account $account)
    {
        $this->setLocation($account, Client::CHECKIN_ACTION);
    }

    /**
     * @param Account $account
     */
    public function checkOut(Account $account)
    {
        $this->setLocation($account, Client::CHECKOUT_ACTION);
    }

    /**
     * @return mixed
     */
    public function locations(): Collection
    {
        $data =  $this->client->getCheckin($this->getToken());
        Collection::wrap($data)->map(function (array $locData){
            if (! $location = Location::query()->find(array_get($locData,'ID'))) {
                Location::create([
                    'id' => array_get($locData,'ID'),
                    'lat' => array_get($locData,'Latitude'),
                    'lng' => array_get($locData,'Longitude'),
                    'directum_id' => array_get($locData,'DirectumID'),
                    'created_at' => Carbon::parse(array_get($locData,'Date')),
                    'is_checked_out' => (bool) array_get($locData,'IsCheckedOut')
                ]);

            } else {
                $location->update([
                    'is_checked_out' => (bool) array_get($locData,'IsCheckedOut')
                ]);
            }

        });

        return Location::query()
            ->orderBy('id','asc')
            ->limit(10)
            ->with(['account'])
            ->whereDate('created_at','>=',now()->subDays(2)->startOfDay())
            ->get();
    }

    public function checkins()
    {
        return Checkin::hydrate($this->locations());
    }
}
