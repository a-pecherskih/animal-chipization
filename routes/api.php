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

Route::group(['middleware' => ['auth.basic']], function () {

    Route::post('registration', [\App\Http\Controllers\AccountController::class, 'registration'])->name('registration');

    Route::group([
        'prefix' => 'accounts',
        'as' => 'account.',
        'controller' => \App\Http\Controllers\AccountController::class
    ], function () {
        Route::get('/search', 'search')->name('search');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });

    Route::group([
        'prefix' => 'locations',
        'as' => 'location.',
        'controller' => \App\Http\Controllers\LocationController::class
    ], function () {

        Route::get('/{id}', 'show')->name('show');
        Route::post('/', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
    });

    Route::group([
        'prefix' => 'animals',
        'as' => 'animal.',
        'controller' => \App\Http\Controllers\AnimalController::class
    ], function () {

        Route::get('/search', 'search')->name('search');
        Route::get('/{animal}', 'show')->name('show');
        Route::post('/', 'create');
        Route::put('/{animal}', 'update');
        Route::delete('/{animal}', 'delete');

        Route::group([
            'prefix' => 'types',
            'as' => 'type.',
            'controller' => \App\Http\Controllers\AnimalTypeController::class
        ], function () {
            Route::get('/{animalType}', 'show')->name('show');
            Route::post('/', 'create');
            Route::put('/{animalType}', 'update');
            Route::delete('/{animalType}', 'delete');
        });

        Route::group([
            'prefix' => '{animal}/types',
            'as' => 'type.',
            'controller' => \App\Http\Controllers\Animal\TypeController::class
        ], function () {
            Route::post('/{animalType}', 'create');
            Route::put('/', 'update');
            Route::delete('/{animalType}', 'delete');
        });

        Route::group([
            'prefix' => '{animal}/locations',
            'as' => 'location.',
            'controller' => \App\Http\Controllers\Animal\VisitedLocationController::class
        ], function () {
            Route::get('/', 'search')->name('search');
            Route::post('/{location}', 'create');
            Route::put('/', 'update');
            Route::delete('/{animalLocation}', 'delete');
        });
    });
});
