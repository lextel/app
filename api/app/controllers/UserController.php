<?php

class UserController extends \BaseController {

    /**
     *
     *打开登录页面
     *
     */
    public function getSignIn()
    {
        //检测是否已经登录
        if (Auth::check()){
            return Redirect::to('/');
        }
        $res = ['code'=>1, 'msg'=>'输入账号/密码格式不符合'];
        return Response::json($res);
    }

    /**
     *
     * 登录验证
     *
     */
    public function signIn()
    {
        $username = trim(Input::get('username'));
        $password = trim(Input::get('password'));
        //验证输入是否符合格式
        $validator = Validator::make(
        [
            'username' => $username,
            'password' => $password,
        ],[
            'username' => 'required',
            'password' => 'required|min:6|max:20',
        ]);
        if ($validator->fails()){
            $res = ['code'=>1, 'msg'=>'输入账号/密码格式不符合'];
            return Response::json($res);
        }
        //验证是否在数据库
        $user = User::where('username', '=', $username)->first();
        if (! $user){
            $res = ['code'=>1, 'msg'=>'账户不存在'];
            return Response::json($res);
        }
        //验证密码是否正确,改动check
        $userhash = new UserHash();
        if ($userhash->hashmake($password, ['method'=>'pbkdf2']) == $user->password){
            Auth::login($user);
            $res = ['code'=>0, 'msg'=>'登录成功'];
            return Response::json($res);
        }
        $res = ['code'=>1, 'msg'=>'用户密码错误'];
        return Response::json($res);
    }

    /**
     *
     *  打开注册页面
     *
     */
    public function getSignUp()
    {
        //检测是否已经登录
        if (Auth::check()){
            return Redirect::to('/');
        }
        return View::make('signup');
    }

    /**
     * 注册验证
     */
    public function signUp()
    {
        $username = trim(Input::get('username'));
        $password = trim(Input::get('password'));
        $nickname = trim(Input::get('nickname'));
        //验证是否符合格式
        $validator = Validator::make(
        [
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
        ],[
            'username' => 'required',
            'password' => 'required|min:6|max:20',
            'nickname' => 'required|min:2|max:8',
        ]);
        if ($validator->fails()){
            $res = ['code'=>1, 'msg'=>'输入不符合格式'];
            return Response::json($res);
        }
        //验证是否存在username
        if (Member::where('username', '=', $username)->first()){
            $res = ['code'=>1, 'msg'=>'您注册的账户已经存在'];
            return Response::json($res);
        }
        //验证是否有昵称
        if (Member::where('nickname', '=', $nickname)->first()){
            $res = ['code'=>1, 'msg'=>'您注册的昵称已经存在'];
            return Response::json($res);
        }
        $userhash = new UserHash();
        Member::create([
          'username' => $username,
          'password' => $userhash->hashmake($password, ['method'=>'pbkdf2']),   // 生成密码.
          'email' => $username,
          'nickname' => $nickname,
        ]);

        $user = User::where('username', '=', $username)->first();

        if (! $user){
            $res = ['code'=>1, 'msg'=>'用户不存在'];
            return Response::json($res);
        }
        Auth::login($user);
        $res = ['code'=>0, 'msg'=>'注册成功'];
        return Response::json($res);
    }

    /**
     *
     *退出登录
     *
     *
     */
    public function signOut()
    {
        Auth::logout();
        return Redirect::to('/');
    }

}
