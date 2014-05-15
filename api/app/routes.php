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
//头部跨域设置
header("Access-Control-Allow-Origin: *");


Route::get('/', function()
{
    return View::make('hello');
});

Route::group(['prefix' => '/'], function() {
    //用户注册\登录功能
    //Route::get('signin', 'UserController@getSignIn');
    Route::post('signin', ['before' => 'postForm|imei', 'uses' => 'UserController@signIn']);
    //Route::get('signup', 'UserController@getSignUp');
    //Route::post('signup', 'UserController@signUp');
    Route::get('signout', ['before' => 'imei|token', 'uses' => 'UserController@signOut']);
    Route::get('userinfo', ['before' => 'imei|token', 'uses' => 'UserController@userInfo']);
});
Route::get('applog', ['before' => '', 'uses' =>'ApplogsController@index']);
//Route::post('applog', ['before' => 'postForm|imei', 'uses' => 'ApplogsController@create']);

Route::post('apps', ['before' => 'postForm|imei', 'uses' => 'AppsController@index']);

Route::get('amount', ['before' => 'imei', 'uses' =>'AppsController@amount']);

Route::get('operate', ['before' => 'imei', 'uses' =>'ApplogsController@download']);
Route::post('operate', ['before' => 'postForm|imei', 'uses' =>'ApplogsController@operate']);

Event::listen('illuminate.query', function($sql)
{
   Log::info($sql);
});
