<?php

namespace App\Http\Controllers;

use App\Account;
use App\Facilicom\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var Service */
    protected $facilicom;

    /**
     * @param Service $facilicom
     */
    public function __construct(Service $facilicom)
    {
        $this->facilicom = $facilicom;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function main()
    {
        return $this->page();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkin(Request $request)
    {
        /** @var Account $account */
        $account = Account::findOrFail($request->input('account'));
        $this->facilicom->checkIn($account);

        return redirect('/');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkout(Request $request)
    {
        /** @var Account $account */
        $account = Account::findOrFail($request->input('account'));
        $this->facilicom->checkOut($account);

        return redirect('/');
    }

    protected function page()
    {
        $this->facilicom->checkins();
        /** @var Collection $locations */
        $checkins = $this->facilicom->checkins();
        $canCheckin = true;
        foreach ($checkins as $checkin){
            if (! $checkin->isCheckedOut() ){
                $canCheckin = false;
            }
        }

        return response()->view('index',
            [
                'canCheckin' => $canCheckin,
                'accounts' => Account::query()->orderByRaw('sort_order, name')->get(),
                'checkins' => $checkins
            ]);
    }
}
