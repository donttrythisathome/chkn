<?php

namespace App\Console;

use App\Account;
use App\Facilicom\Client;
use Illuminate\Console\Command;

class FillAccounts extends Command
{
    /** @var Client  */
    protected $client;

    /** @var string  */
    protected $signature = 'fill-accounts';

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle()
    {
        $token = $this->client->logon()['Token'];
        $filled = Account::all();
        foreach ($this->client->getAccounts($token) as $account){
            if (! $filled->contains('id','=',$account['Id'])){
                Account::create([
                    'id' => $account['Id'],
                    'name' => $account['Name'],
                    'address' => $account['Address']
                ]);
            }
        }
    }
}
