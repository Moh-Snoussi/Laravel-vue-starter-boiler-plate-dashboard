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




Route::get(env('PROVIDERS_CALL') . '{provider}', 'AuthController@redirectToProvider'); // soial login redirection
Route::get(env('PROVIDERS_REDIRECT') . '{provider}', 'AuthController@handleProviderCallback');// redirection from social provider this route have to match the provider callback on .inv and on api callback setup on provider platform

// redirection from email page will display user email on login

Route::get('/login', function () {

	$arr['email'] = isset($_GET['email']) ? $_GET['email'] : '';

	$arr['isconfirmed'] = isset($_GET['isconfirmed']) ? $_GET['isconfirmed'] : ''; 
	// passing query parameters to the frondend
	return View('welcome')->with(['email' => $arr['email'], 'isconfirmed' => $arr['isconfirmed']]);

})->name('login');// naming the route

Route::any(
	'{query}',
	function () {
		return redirect()->route('login');
	}
)
	->where('query', '.*'); // all others routes they will be catched here

// Route::get('/login', function () {// we are handel wecome accordigly 

// 	$arr['email'] = isset($_GET['email']) ?   $_GET['email'] : '';

// 	$arr['isconfirmed'] = isset($_GET['isconfirmed']) ? $_GET['isconfirmed'] : '';
		
// return View('welcome')->with(['email'=> $arr['email'], 'isconfirmed' => $arr['isconfirmed']  ]);
// })->name('login');


////****:data-message="{{ json_encode([$email , $isconfirmed])}}