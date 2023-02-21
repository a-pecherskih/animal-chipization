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

Route::group([
    'prefix' => 'accounts',
    'middleware' => ['auth.basic'],
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
    'middleware' => ['auth.basic'],
    'as' => 'location.',
    'controller' => \App\Http\Controllers\LocationController::class
], function () {
    Route::get('/{location}', 'show');
    Route::post('/', 'create');
    Route::put('/{location}', 'update');
    Route::delete('/{location}', 'delete');
});
