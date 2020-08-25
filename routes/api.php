<?php

use Illuminate\Http\Request;
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


// API Routes
Route::group(['middleware' => 'api'], function () {

    // Auth Routes
    Route::group(['namespace' => 'Auth\Controllers', 'middleware' => 'auth:api', 'prefix' => 'auth'], function () {
        Route::delete('logout', 'AuthController@logout');
    });
    Route::group(['namespace' => 'Auth\Controllers', 'prefix' => 'auth'], function () {
        Route::post('token', 'AuthController@login');
        Route::post('register', 'AuthController@register');
    });

});
