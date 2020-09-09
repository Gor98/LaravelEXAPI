<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function () {
    // Auth Routes
    Route::group(['namespace' => 'Auth\Controllers', 'prefix' => 'auth'], function () {
        Route::post('token', 'AuthController@login')->name('login');
        Route::post('register', 'AuthController@register')->name('register');
    });
    Route::group(['namespace' => 'Auth\Controllers', 'middleware' => 'auth:api', 'prefix' => 'auth'], function () {
        Route::delete('logout', 'AuthController@logout')->name('logout');
    });

    // User Routes
    Route::group(['namespace' => 'Auth\Controllers', 'middleware' => 'auth:api'], function () {
        Route::apiResource('users', 'UserController');
    });
});
