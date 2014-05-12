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

//适应angularjs POST格式参数FORM-DATA
Route::filter('postForm', function()
{
    $imei = trim(Input::get('imei', ''));
    if (empty($imei)){
        $rawpostdata = file_get_contents("php://input");
        $post = json_decode($rawpostdata, true);
        Input::merge($post);
    }
});

//检测是否是手机的
Route::filter('imei', function()
{
    $imei = trim(Input::get('imei', ''));
    if (empty($imei)){
        $res = ['code'=>1, 'msg'=>'非手机平台登录，不能操作'];
        return Response::json($res);
    }
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
    Route::get('signout', ['uses' => 'UserController@signOut']);
});
Route::get('applog', ['before' => 'imei', 'uses' =>'ApplogsController@index']);
Route::post('applog', ['before' => 'postForm', 'uses' => 'ApplogsController@create']);

Route::post('apps', ['before' => 'postForm|imei', 'uses' => 'AppsController@index']);

Route::get('amount', ['before' => 'imei', 'uses' =>'AppsController@amount']);

Event::listen('illuminate.query', function($sql)
{
   Log::info($sql);
});
