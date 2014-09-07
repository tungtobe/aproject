<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showWelcome');
Route::get('/login','AuthenController@getLogin');
Route::get('/logout','AuthenController@getLogout');
Route::get('/loginfb','AuthenController@loginWithFacebook');
Route::get('/twitter', 'AuthenController@getLoginwithTwitter');
Route::get('/callback', 'AuthenController@TwitterCallback');
Route::get('/logged', 'AuthenController@showUserCredentials');

Route::get('/video/{id}','VideoController@showVideo');
Route::post('/video/upload','VideoController@upload');

Route::get('/user/{id}','UserController@getShow');

Route::group(array('before' => 'app.auth'), function() {
	Route::post('/comment/postStore','CommentController@postStore');
});

Route::filter('app.auth',function(){
     if(Auth::guest()) {
         return Redirect::guest(URL::action('AuthenController@getLogin'));
    }
});