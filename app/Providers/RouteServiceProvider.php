<?php

namespace App\Providers;

use App\Exceptions\BadRequestException;
use App\Exceptions\ModelNotFoundException;
use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\Location;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        Route::bind('account', function ($value) {

            if (blank($value) || $value <= 0) throw new BadRequestException();

            return User::query()->firstWhere('id', $value) ?? throw new ModelNotFoundException;
        });
        Route::bind('animalType', function ($value) {

            if (blank($value) || $value <= 0) throw new BadRequestException();

            return AnimalType::query()->firstWhere('id', $value) ?? throw new ModelNotFoundException;
        });
        Route::bind('location', function ($value) {

            if (blank($value) || $value <= 0) throw new BadRequestException();

            return Location::query()->firstWhere('id', $value) ?? throw new ModelNotFoundException;
        });
        Route::bind('animal', function ($value) {

            if (blank($value) || $value <= 0) throw new BadRequestException();

            return Animal::query()->firstWhere('id', $value) ?? throw new ModelNotFoundException;
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
