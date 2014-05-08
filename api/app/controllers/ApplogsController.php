<?php

class ApplogsController extends \BaseController {
    private $page_size = 10;
    /**
     * 用户APP积分日志列表，根据用户ID获取
     *
     * @return Response
     */
    public function index()
    {
        //检测TOKEN
        $token = trim(Input::get('token', ''));
        if (empty($token)){
            $res = ['code'=>1, 'msg'=>'请输入TOKEN'];
            return Response::json($res);
        };
        $tokenClass = new TokenClass;
        $user = $tokenClass->check($token);
        if (! $user){
            $res = ['code'=>1, 'msg'=>'请登陆'];
            return Response::json($res);
        }
        $pn = trim(Input::get('pn', 0));
        $userId = $user->id;
        $count = Applog::where('member_id', '=', $userId)->count();
        $logs = [];
        if ($count > $pn){
            $logs = Applog::select('id', 'title', 'award', 'created_at')
                ->where('member_id', '=',$userId)
                ->orderBy('id', 'desc')
                ->take($this->page_size)
                ->skip($pn)
                ->get()->toArray();
        }
        foreach($logs as &$row){
             //$row['created_at'] = date("Y-m-d H:i", $row['created_at']);
            $row['created_at'] = $row['created_at'];
        }
        $data = ['logs'=>$logs, 'count'=>$count];
        $res = ['code'=>0, 'msg'=>'OK', 'data'=>$data];
        return Response::json($res);
    }

    /**
     * 增加新APP奖励日志
     *
     * @return Response
     */
    public function create()
    {
        //如何验证APPID的有效性是个问题
        $imei = trim(Input::get('imei', ''));
        $package = trim(Input::get('package', ''));
        $award = 0;
        $member_id = 0;
        $username = '';
        $status = 0;
        $logined = false;
        if (empty($imei)){
            $res = ['code'=>1, 'msg'=>'非手机平台登录，不能操作'];
            return Response::json($res);
        }
        //检测该APPID是否存在
        $appInfo = Apps::where('package', '=', $package)
                      ->where('is_delete', '=', 0)
                      ->where('status', '=', '1')
                      ->first();
        if (empty($appInfo)){
            $res = ['code'=>1, 'msg'=>'不存在该APP'];
            return Response::json($res);
        }
        //检测该APPID在该IMEI上是否使用过
        $appLog = Applog::where('package', '=', $package)
                      ->where('imei', '=', $imei)
                      ->first();
        if ($appLog){
            $res = ['code'=>1, 'msg'=>'该APPID在这设备上已经使用过了'];
            return Response::json($res);
        }
        $award = intval($appInfo->award);
        //已登录
        $token = new TokenClass;
        $user = $token->check();
        if ($user){
            $member_id = $user->id;
            $username = $user->username;
            $status = 1;
            $logined = true;
        }
        //保存日志
        Applog::create([
                    'app_id' => $appInfo->id,
                    'package' => $appInfo->package,
                    'title' => $appInfo->title,
                    'imei' => $imei,
                    'status' => $status,
                    'award' => $award,
                    'member_id' => $member_id,
                    'username' => $username,
                    ]);
        //登录了立马结算
        if ($logined){
            $user->points += intval($award);
            $user->save();
            //记录明细
            Moneylog::create([
                'phase_id'  => 0,
                'total'     => $award / Config::get('common.point', 100),
                'sum'       => $award,
                'type'      => 3,
                'source'    => 'APP下载',
                'member_id' => $user->id,
            ]);
        }
        $res = ['code'=>0,'msg'=>'OK'];
        return Response::json($res);
    }
}
