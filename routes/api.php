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
        Route::post('/', 'create')->name('create');
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
        Route::post('/', 'create');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
    });

    Route::group([
        'prefix' => 'animals',
        'as' => 'animal.',
        'controller' => \App\Http\Controllers\AnimalController::class
    ], function () {

        Route::get('/search', 'search')->name('search');
        Route::get('/{animalId}', 'show')->name('show');
        Route::post('/', 'create');
        Route::put('/{animalId}', 'update');
        Route::delete('/{animalId}', 'delete');

        Route::group([
            'prefix' => 'types',
            'as' => 'type.',
            'controller' => \App\Http\Controllers\AnimalTypeController::class
        ], function () {
            Route::get('/{id}', 'show')->name('show');
            Route::post('/', 'create');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

        Route::group([
            'prefix' => '{animalId}/types',
            'as' => 'type.',
            'controller' => \App\Http\Controllers\Animal\TypeController::class
        ], function () {
            Route::post('/{typeId}', 'create');
            Route::put('/', 'update');
            Route::delete('/{typeId}', 'delete');
        });

        Route::group([
            'prefix' => '{animalId}/locations',
            'as' => 'location.',
            'controller' => \App\Http\Controllers\Animal\VisitedLocationController::class
        ], function () {
            Route::get('/', 'search')->name('search');
            Route::post('/{pointId}', 'create');
            Route::put('/', 'update');
            Route::delete('/{pointId}', 'delete');
        });
    });

    Route::group([
        'prefix' => 'areas',
        'as' => 'area.',
        'controller' => \App\Http\Controllers\AreaController::class
    ], function () {
        Route::get('/search', 'search')->name('search');
        Route::get('/{areaId}/analytics', [\App\Http\Controllers\Area\AnalyticController::class, 'analyze'])->name('analyze');
        Route::post('/', 'create')->name('create');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });
});
