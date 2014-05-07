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
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: Origin, No-Cache, X-Requested-With, Content-Type, Accept");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//you yimen cichu??
Route::filter('postForm', function()
{
        $rawpostdata = file_get_contents("php://input");
        $post = json_decode($rawpostdata, true);
        Input::merge($post);
});

Route::get('/', function()
{
    return View::make('hello');
});
Route::group(['prefix' => '/'], function() {
    //用户注册\登录功能
    //Route::get('signin', 'UserController@getSignIn');
    Route::post('signin', ['before' => 'postForm', 'uses' => 'UserController@signIn']);
    //Route::get('signup', 'UserController@getSignUp');
    //Route::post('signup', 'UserController@signUp');
    Route::get('signout', 'UserController@signOut');
});
Route::get('applog', ['uses' =>'ApplogsController@index']);
Route::post('applog', ['before' => 'postForm', 'uses' => 'ApplogsController@create']);

Route::post('apps', ['before' => 'postForm', 'uses' => 'AppsController@index']);

Event::listen('illuminate.query', function($sql)
{
   Log::info($sql);
});
