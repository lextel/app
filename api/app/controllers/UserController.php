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
            $res = ['code'=>2, 'msg'=>'已经登录'];
            return Response::json($res);
        }
        $res = ['code'=>0, 'msg'=>'去登录'];
        return Response::json($res);
    }

    /**
     *
     * 登录验证
     *
     */
    public function signIn()
    {       
        //检测是否已经登录
        if (Auth::check()){
            $res = ['code'=>2, 'msg'=>'已经登录'];
            return Response::json($res);
        }
        $member = new Member();
        $username = trim(Input::get('username'));
        $password = trim(Input::get('password'));
        //验证输入是否符合格式
        $validator = $member->validateSignIn($username, $password);
        if ($validator->fails()){
            $msg = '';
            $messages = $validator->messages();
            foreach ($messages->all(':message') as $message){
                $msg = $message;
                break;
            }
            $res = ['code'=>1, 'msg'=>$msg];
            return Response::json($res);
        }
        //验证是否在数据库
        $user = User::where('username', '=', $username)->first();
        if (! $user){
            $res = ['code'=>1, 'msg'=>'账户不存在'];
            return Response::json($res);
        }
        //验证密码是否正确,改动check        
        if ($member->setPassword($password) == $user->password){
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
        $res = ['code'=>0, 'msg'=>'去注册'];
        return Response::json($res);
    }

    /**
     * 注册验证
     */
    public function signUp()
    {
        //检测是否已经登录
        if (Auth::check()){
            $res = ['code'=>2, 'msg'=>'已经登录'];
            return Response::json($res);
        }
        $member = new Member();
        $username = trim(Input::get('username'));
        $password = trim(Input::get('password'));
        $nickname = trim(Input::get('nickname'));
        $validator = $member->validateSignUp($username, $password, $nickname);
        if ($validator->fails()){
            $msg = '';
            $messages = $validator->messages();
            foreach ($messages->all(':message') as $message){
                $msg = $message;
                break;
            }
            $res = ['code'=>1, 'msg'=>$msg];
            return Response::json($res);
        }
        //保存
        $member->createMember($username, $password, $nickname);       
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
