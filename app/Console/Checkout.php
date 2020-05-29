<?php

namespace App\Console;

use App\Account;
use App\Facilicom\Service;
use Illuminate\Console\Command;

class Checkout extends Command
{
    /** @var string  */
    protected $signature = 'checkout {account}';

    /** @var Service */
    protected $facilicom;

    public function __construct(Service $facilicom)
    {
        parent::__construct();
        $this->facilicom = $facilicom;
    }

    public function handle()
    {
        /** @var Account $account */
        $account = Account::findOrFail($this->argument('account'));

        $this->facilicom->checkOut($account);
    }

}
