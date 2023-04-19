<?php

namespace App\Providers;

use App\Packages\Geometry\Geo\GeoGeometry;
use App\Packages\Geometry\Geometry;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Geometry::class, function($app) {
            return new GeoGeometry();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
