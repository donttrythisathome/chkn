<?php

namespace App\Console;

use App\Account;
use App\Facilicom\Client;
use App\Geocode\Geocode;
use Illuminate\Console\Command;

class FillAccounts extends Command
{
    /** @var Client  */
    protected $client;

    /** @var string  */
    protected $signature = 'fill-accounts';

    /** @var Geocode  */
    protected $geocode;

    /**
     * @param Client $client
     * @param Geocode $geocode
     */
    public function __construct(Client $client, Geocode $geocode)
    {
        parent::__construct();
        $this->client = $client;
        $this->geocode = $geocode;
    }

    public function handle()
    {
        $token = $this->client->logon()['Token'];
        $filled = Account::query()->select(['id'])->get();
        foreach ($this->client->getAccounts($token) as $account){
            if (! $filled->contains('id','=',$account['Id'])){
                $location = $this->geocode->getLocation($account['Address']);
                Account::query()->create([
                    'id' => $account['Id'],
                    'name' => $account['Name'],
                    'address' => $account['Address'],
                    'lat' => $location[1],
                    'lng' => $location[0]
                ]);
            }
        }
    }

}
