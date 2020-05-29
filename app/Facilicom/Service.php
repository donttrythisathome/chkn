<?php

namespace App\Facilicom;

use App\Account;
use App\Location;
use Illuminate\Database\Eloquent\Collection;

class Service
{
    /** @var Client  */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setLocation(Account $account, int $actionType)
    {
        $token = $this->client->logon()['Token'];
        $this->client->setLocation(
            $token,
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
    public function locations()
    {
        $token = $this->client->logon()['Token'];
        $data =  $this->client->getCheckin($token);

        /** @var Collection $locations */
        $locations =  Location::hydrate($data);

        return $locations->load(['account']);
    }
}
