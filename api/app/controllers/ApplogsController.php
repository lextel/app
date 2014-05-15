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
     * APP操作日志记录
     *
     * @return Response
     */
    public function operate()
    {
        //如何验证APPID的有效性是个问题
        $imei = trim(Input::get('imei', ''));
        $package = trim(Input::get('package', ''));
        $action = trim(Input::get('action', ''));
        $token = trim(Input::get('token', ''));
        $type = ['download'=> 0, 'downloaded'=> 2, 'install'=> 3, 'execute'=> 4, 'completed'=> 5];
        if (empty($action) or (!array_key_exists($action, $type))){
            $res = ['code'=>1, 'msg'=>'不存在该ACTION'];
            return Response::json($res);
        }
        $status = $type[$action];
        //检测该APPID是否存在
        $appInfo = Apps::where('package', '=', $package)
                      ->where('is_delete', '=', 0)
                      ->where('status', '=', '1')
                      ->first();
        if (empty($appInfo)){
            $res = ['code'=>1, 'msg'=>'不存在该APP'];
            return Response::json($res);
        }
        //检测是否执行过前面的步骤
        if ($status > 0){
            $appLog = Applog::where('package', '=', $package)
                      ->where('imei', '=', $imei)
                      ->where('status', '=', $status - 1)
                      ->first();
            if (!$appLog){
                $res = ['code'=>1, 'msg'=>'该操作的上一级操作不能跳过'];
                return Response::json($res);
            }
        }        
        if ($status != 5){
            $appLog = Applog::firstOrCreate([
                'app_id' => $appInfo->id,
                'package' => $appInfo->package,
                'title' => $appInfo->title,
                'imei' => $imei,
                'status' => $status,
                'award' => 0,
                'member_id' => 0,
                'username' => '',
                ]);
            $res = ['code'=>0,'msg'=>'OK'];
            return Response::json($res);
        }
        //结算
        $award = $appInfo->award;
        //已登录
        $tokenclass = new TokenClass;
        $user = $tokenclass->check($token);
        //记录日志
        Appexist::firstOrCreate(['package'=>$appInfo->package, 'imei'=>$imei]);
        $app = Applog::firstOrCreate([
                'app_id' => $appInfo->id,
                'package' => $appInfo->package,
                'title' => $appInfo->title,
                'imei' => $imei,
                'award' => $award
                ]);
        $app->status = $user ? 6 : 5;
        $app->member_id = $user ? $user->id : '';
        $app->username = $user ? $user->username : '';
        $app->save();
        //登录了立马结算
        if ($user){
        
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
    
    /*
    * APP要下载日志记录
    */
    public function download()
    {
        $imei = trim(Input::get('imei', ''));
        $id = trim(Input::get('id', ''));
        //检测该APPID是否存在
        $appInfo = Apps::where('id', '=', $id)
                      ->where('is_delete', '=', 0)
                      ->where('status', '=', '1')
                      ->first();
        if (empty($appInfo)){
            $res = ['code'=>1, 'msg'=>'不存在该APP'];
            return Response::json($res);
        }
        //检测该APPID在该IMEI上是否使用过
        $appLog = Applog::where('app_id', '=', $id)
                      ->where('imei', '=', $imei)
                      ->where('status', '=', 0)
                      ->first();
        //没有则新建立个
        if (! $appLog){           
            $res = ['code'=>1, 'msg'=>'不能直接跳过点击下载'];
            return Response::json($res);
        }
        $appLog = Applog::firstOrCreate([
                'app_id' => $appInfo->id,
                'package' => $appInfo->package,
                'title' => $appInfo->title,
                'imei' => $imei,
                'status' => 1,
                'award' => 0,
                'member_id' => 0,
                'username' => '',
                ]);
        //302跳转
        $url = Helper::urlPro($appInfo->link);
        //return Redirect::away($url);
        //文件流输出
        $file = @ fopen($url, "r");
        if (!$file) {
            $res = ['code'=>1, 'msg'=>'', 'url'=>$url];
            return Response::json($res);
        } else {
            Header("Content-type: application/octet-stream");
            Header("Content-Disposition: attachment; filename=" . $appInfo->link);
            while (!feof ($file)) {
                echo fread($file,50000);
        }
        fclose($file);
        }
        //$res = ['code'=>0, 'msg'=>'', 'url'=>$url];
        //return Response::json($res);
    }
    
}
