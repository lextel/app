<?php

class AppsController extends \BaseController {

    /**
     * 匹配APP是否已经安装的列表
     *
     * @return Response
     */
    public function index()
    {
        $imei = trim(Input::get('imei', ''));
        $installedApps = Input::get('apps');
        //默认为安卓
        $os = trim(Input::get('os', '1'));
        if (!is_array($installedApps)){
             $res = ['code'=>1, 'msg'=>'app格式必须为[]'];
            return Response::json($res);
        }
        //先检测本机现有的已经安装了的APP，并记录到表
        if (empty($installedApps)) $installedApps = [];
        //这处性能需要改动下
        foreach($installedApps as $app){
            //过滤掉安卓系统自身的
            preg_match("/^com.android.*$/", $app, $matches);
            if (!$matches){
                Appexist::firstOrCreate(['package'=>$app, 'imei'=>$imei]);
            }        
        }
        //检测是否已经安装了
        $apps = Appexist::select('package')
            ->where('imei', '=', $imei)
            ->get()->toArray();
        $logsTmp = [];
        foreach ($apps as $index) {
            $logsTmp[] = $index['package'];
        }
        //获得剩余的,2LIST做并集去重处理
        $logs = array_unique(array_merge($logsTmp, $installedApps));
        $apps = [];
        if (empty($logs)) $logs = [0];
        $apps = Apps::select('id', 'package', 'title', 'icon', 'award', 'size', 'images', 'summary', 'link')
                    ->whereNotIn('package', $logs)
                    ->where('is_delete', '=', '0')
                    ->where('status', '=', '1')
                    ->where('os', '=', $os)
                    ->get()->toArray();
        foreach($apps as &$row){
             $images = [];
             foreach(unserialize($row['images']) as $img){
                $images[] = Helper::urlPro($img, 'img');
             }
             $row['images'] = $images;
             $row['link'] = Helper::urlPro($row['link'], 'link');
             $row['icon'] = Helper::urlPro($row['icon'], 'img');
        }

        $data = ['apps'=>$apps, 'count'=>count($apps)];
        $res = ['code'=>0,'msg'=>'OK', 'data'=>$data];
        return Response::json($res);
    }

    /*
    * 获得APP市场的剩余的未使用的余额，登录后会清零
    */
    public function amount()
    {
        $imei = trim(Input::get('imei', ''));
        $logs = Awardlog::select('id', 'award')
                            ->where('imei', '=', $imei)
                            ->where('status', '=', 5)->get()->toArray();
        $amount = 0;
        foreach($logs as $index){
            $amount += $index['award'];
        }
        //分开计算金币和银币
        $gold = intval($amount / Config::get('common.point', 100));
        $silver = $amount % Config::get('common.point', 100);
        $data = ['gold'=>$gold, 'silver'=>$silver, 'amount'=>$amount];
        $res = ['code'=>0,'msg'=>'OK', 'data'=>$data];
        return Response::json($res);
    }
}
