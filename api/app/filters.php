<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

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
//
Route::filter('token', function()
{
    $token = trim(Input::get('token', ''));
    if (empty($token)){
        $res = ['code'=>1, 'msg'=>'请输入TOKEN'];
        return Response::json($res);
    }
});