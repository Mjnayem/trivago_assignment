<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1', 'middleware' => 'cors'], function () {

    Route::post('register', [
        'as' => 'register',
        'uses' => '\App\Http\Controllers\Auth\RegisterController@register'
    ]);

    Route::post('login', [
        'as' => 'login',
        'uses' => '\App\Http\Controllers\Auth\LoginController@login'
    ]);



    Route::group(['middleware' => 'auth:api'], function () {

        Route::get('logout', '\App\Http\Controllers\Auth\AuthController@logout');



        Route::group(['prefix' => 'user'], function () {

            Route::post('addItem', [
                'as' => 'addItem',
                'uses' => '\App\Http\Controllers\ItemController@addItemUser'
            ]);
        });
    });

});
