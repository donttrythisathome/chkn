<?php

namespace App\Providers;

use App\Facilicom\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class FacilicomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Client::class, function (Application $app) {
            $guzzle = $app->make(\GuzzleHttp\Client::class);
            $baseUrl = $app['config']['facilicom.base_url'];
            $login = $app['config']['facilicom.login'];
            $password = $app['config']['facilicom.password'];

            return new Client($guzzle, $baseUrl, $login, $password);
        });
    }
}
