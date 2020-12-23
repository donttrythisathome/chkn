<?php

namespace App\Providers;

use App\Geocode\Geocode;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class GeocodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGeocode();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function registerGeocode()
    {
        $this->app->bind(Geocode::class, function (Application $app) {
            return new Geocode(
                $this->config('api_key'),
                $this->config('base_uri'),
                $app->make(Client::class)
            );
        });
        $this->app->alias(Geocode::class, 'geocode');
    }

    /**
     * @param string $offset
     * @return mixed
     */
    protected function config(string $offset)
    {
        return $this->app['config']["geocode.$offset"];
    }
}
