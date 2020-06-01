<?php

namespace App\Facilicom;

use App\Account;
use App\Checkin;
use App\Location;
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

        /** @var Collection $locations */
        $locations =  Location::hydrate($data);

        return $locations->load(['account']);
    }

    public function checkins()
    {
        return Checkin::hydrate($this->locations());
//        $d->each(function (Checkin $checkin){
//            dd($checkin->getDuration()->format("%h:%i"));
//        });
    }
}
