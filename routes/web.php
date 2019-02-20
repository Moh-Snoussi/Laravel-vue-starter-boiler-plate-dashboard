<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/**
 * . '{provider}' is a dynamic text in the url this need to be google, facebook, elc..
 * 'AuthController@redirectToProvider'  is called if the requested url match as
 * class@method in our case AuthController class that lives on app/Http/Controller/AuthController
 * redirectToProvider is the method
 * env('PROVIDERS_CALL') will get the value from the .env file in the root folder
 */

Route::get('logout', 'AuthController@logout');
Route::get(env('PROVIDERS_CALL') . '{provider}', 'AuthController@redirectToProvider'); // social login redirection
Route::get(env('PROVIDERS_REDIRECT') . '{provider}', 'AuthController@handleProviderCallback');// redirection from social provider this route have to match the provider callback on .inv and on api callback setup on provider platform

// redirection from email page will display user email on login

Route::get('/login', function () {

	$arr['email'] = isset($_GET['email']) ? $_GET['email'] : '';

	$arr['isconfirmed'] = isset($_GET['isconfirmed']) ? $_GET['isconfirmed'] : ''; 
	// passing query parameters to the frondend
	return View('welcome')->with(['email' => $arr['email'], 'isconfirmed' => $arr['isconfirmed']]);

})->name('login');// naming the route

Route::get('/dashboard/{vue_capture?}', function () {
	return view('welcome');
   })->where('vue_capture', '[\/\w\.-]*');// naming the route

// all others routes they will be catched here
Route::any('{query}', function () {
	return redirect()->route('login');
})->where('query', '.*'); 


