<?php

class UserController extends \BaseController {
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
        $member = new Member();
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
        //验证用户是否在数据库
        $user = Member::where('username', '=', $username)
                      ->where('is_disable', '=', 0)
                      ->where('is_delete', '=', 0)->first();
        if (! $user){
            $res = ['code'=>1, 'msg'=>'账户不存在'];
            return Response::json($res);
        }
        //验证密码是否正确,改动check
        if ($member->setPassword($password) != $user->password){
            $res = ['code'=>1, 'msg'=>'用户密码错误'];
            return Response::json($res);
        }
        //登录
        $token = new TokenClass;
        $apptoken = $token->create($user);
        //结算本次登录的机器的 功能需要剥离下
        $logs = Applog::select('id', 'award')
                            ->where('imei', '=', $imei)
                            ->where('status', '=', 5)->get()->toArray();
        $addPoints = 0;
        $ids = [];
        //这地方需要改进下//更改日志记录-减少请求次数
        foreach($logs as $index){
            $addPoints += intval($index['award']);
            $ids[] = $index['id'];
            $log = Applog::find($index['id']);
            $log->status = 6;
            $log->member_id = $user->id;
            $log->username = $user->username;
            $log->save();
        }
        //结算
        if ($addPoints > 0){
            $user->points += $addPoints;
            $user->save();
            //记录明细
            Moneylog::create([
                'phase_id'  => 0,
                'total'     => $addPoints / Config::get('common.point', 100),
                'sum'       => $addPoints,
                'type'      => 3,
                'source'    => 'APP下载',
                'member_id' => $user->id,
            ]);
            }
        $res = ['code'=>0, 'msg'=>'登录成功', 'token'=>$apptoken];
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
        $res = ['code'=>0, 'msg'=>'注册成功'];
        return Response::json($res);
    }
    
    /**
     * 获得用户信息
     */
    public function userInfo()
    {
        $token = trim(Input::get('token', ''));
        $tokenClass = new TokenClass;
        $memberInfo = $tokenClass->check($token);
        if (! $memberInfo) {
            $res = ['code'=>1, 'msg'=>'TOKEN错误'];
            return Response::json($res);
        }
        $data = ['username'=>$memberInfo->username,
                'nickname'=>$memberInfo->nickname,
                'avatar'=>$memberInfo->avatar];
        $res = ['code'=>0, 'msg'=>'OK', 'data'=>$data];
        return Response::json($res);
    }
    
    /**
     *退出登录
     */
    public function signOut()
    {
        $token = trim(Input::get('token', ''));
        $tokenClass = new TokenClass;
        if (! $tokenClass->check($token)) {
            $res = ['code'=>1, 'msg'=>'TOKEN错误'];
            return Response::json($res);
        }
        $tokenClass->delete($token);
        $res = ['code'=>0, 'msg'=>'退出成功'];
        return Response::json($res);
    }
}
