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
    $res = ['code'=>0, 'msg'=>'欢迎来到乐乐淘'];
    return Response::json($res);
});

Route::group(['prefix' => '/'], function() {
    //用户注册\登录功能
    Route::post('signin', ['before' => 'postForm|imei', 'uses' => 'UserController@signIn']);
    Route::get('signout', ['before' => 'imei|token', 'uses' => 'UserController@signOut']);
    Route::get('userinfo', ['before' => 'imei|token', 'uses' => 'UserController@userInfo']);

});

Route::post('apps', ['before' => 'postForm|imei', 'uses' => 'AppsController@index']);
Route::get('amount', ['before' => 'imei', 'uses' =>'AppsController@amount']);
Route::get('applog', ['before' => '', 'uses' =>'ApplogsController@index']);
Route::post('operate', ['before' => 'postForm|imei', 'uses' =>'ApplogsController@operate']);

