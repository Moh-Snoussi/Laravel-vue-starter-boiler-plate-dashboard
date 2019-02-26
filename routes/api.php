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
| AuthController.php is controlling all the logic on app/http/controller/AuthController
|
 */

Route::group([
    'prefix' => 'auth'
], function () {

    Route::post('cardlogin', 'AuthController@loginWithCard')->middleware("throttle:" . (int)env('LOGIN_ATTEMPT', '10') . "," . (int)env('TIME_WAIT', '3')); // login attemps catch
    Route::post('login', 'AuthController@login')->middleware("throttle:8,3"); // login attemps catch for normal login
    Route::post('signup', 'AuthController@signup');// registration 
    Route::get('signup/activate/{id}/{token}', 'AuthController@signupActivate');//email confirmation delivred

    Route::group([
        'middleware' => 'auth:api'
    ], function () {// only authorized users
        Route::get('logout', 'AuthController@logout');//logout
        Route::get('user', 'AuthController@user');// getting the user object
        Route::get('refresh', 'AuthController@refresh'); // JWT refresh
        Route::get('dashboard', 'AccountActivity@dashboard');// dashboard
        Route::post('activity', 'AccountActivity@depositAndWithdraw');// activity for deposit or with draw
        Route::post('transaction', 'AccountActivity@transaction');// transacation
        Route::post('cardchecker', 'AccountActivity@cardChecker');//check the card before transaction
        Route::get('history', 'AccountActivity@index');// table proccessing

    });
});