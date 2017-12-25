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
Route::post('loginn', 'Api\UserController@login');

Route::post('registerr', 'Api\UserController@register');


Route::group(['middleware' => 'auth:api'], function(){

    Route::post('details', 'Api\UserController@details');

});
Route::post('/register', array( 'as' => 'Api.register', 'uses' => 'Api\ApiLoginController@register' ));
Route::put('/login', array( 'as' => 'Api.login', 'uses' => 'Api\ApiLoginController@login' ));
Route::post('/games', array( 'as' => 'Api.start-game', 'uses' => 'Api\ApiStartGameController@startGame' ));
Route::put('/games/{id}', array( 'as' => 'Api.end-game', 'uses' => 'Api\ApiStartGameController@endGame' ));
Route::get('/games/{id}/{token}', array( 'as' => 'Api.game-statistics', 'uses' => 'Api\ApiStatsGameController@gameStatistics' ));

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
