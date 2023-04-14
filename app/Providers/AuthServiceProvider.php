<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalTypeController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\LocationController;
use App\Policies\AccountControllerPolicy;
use App\Policies\AnimalTypeControllerPolicy;
use App\Policies\AreaControllerPolicy;
use App\Policies\LocationControllerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        AccountController::class => AccountControllerPolicy::class,
        LocationController::class => LocationControllerPolicy::class,
        AnimalTypeController::class => AnimalTypeControllerPolicy::class,
        AnimalController::class => AnimalTypeControllerPolicy::class,
        AreaController::class => AreaControllerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
