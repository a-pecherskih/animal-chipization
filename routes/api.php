<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('registration', [\App\Http\Controllers\AccountController::class, 'registration']);


Route::group(['middleware' => ['auth.basic']], function () {
    Route::group([
        'prefix' => 'accounts',
        'as' => 'account.',
        'controller' => \App\Http\Controllers\AccountController::class
    ], function () {
        Route::get('/search', 'search');
        Route::get('/{user}', 'show');

        Route::group(['middleware' => 'auth.current'], function () {
            Route::put('/{user}', 'update')->name('update');
            Route::delete('/{user}', 'delete')->name('delete');
        });
    });

    Route::group([
        'prefix' => 'locations',
        'as' => 'location.',
        'controller' => \App\Http\Controllers\LocationController::class
    ], function () {

        Route::get('/{location}', 'show');
        Route::post('/', 'create');
        Route::put('/{location}', 'update');
        Route::delete('/{location}', 'delete');
    });

    Route::group([
        'prefix' => 'animals',
        'as' => 'animal.',
        'controller' => \App\Http\Controllers\AnimalController::class
    ], function () {

        Route::get('/search', 'search');
        Route::get('/{animal}', 'show');
        Route::post('/', 'create');
        Route::put('/{animal}', 'update');
        Route::delete('/{animal}', 'delete');

        Route::group([
            'prefix' => 'types',
            'as' => 'type.',
            'controller' => \App\Http\Controllers\AnimalTypeController::class
        ], function () {
            Route::get('/{animalType}', 'show');
            Route::post('/', 'create');
            Route::put('/{animalType}', 'update');
            Route::delete('/{animalType}', 'delete');
        });
    });
});
