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
        if (!is_array($installedApps)){
             $res = ['code'=>1, 'msg'=>'apps是必须的，或者不能为空'];
            return Response::json($res);
        }
        //检测是否已经安装了
        $logs = Applog::select('package')
            ->where('imei', '=', $imei)
            ->get()->toArray();
        $logsTmp = [];
        foreach ($logs as $index) {
            $logsTmp[] = $index['package'];
        }
        //获得剩余的
        if (empty($installedApps)) $installedApps = [];
        $logs = array_unique(array_merge($logsTmp, $installedApps));
        $apps = [];
        if (empty($logs)) $logs = [0];
        $apps = Apps::select('id', 'package', 'title', 'icon', 'award', 'size', 'images', 'summary', 'link')
                    ->whereNotIn('package', $logs)
                    ->where('is_delete', '=', '0')
                    ->where('status', '=', '1')
                    ->get()->toArray();
        foreach($apps as &$row){
             $row['images'] = unserialize($row['images']);
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
        $logs = Applog::select('id', 'award')
                            ->where('imei', '=', $imei)
                            ->where('status', '=', 0)->get()->toArray();
        $amount = 0;
        $ids = [];
        //这地方需要改进下//更改日志记录-减少请求次数
        foreach($logs as $index){
            $amount += $index['award'];
        }
        $data = ['amount'=>$amount];
        $res = ['code'=>0,'msg'=>'OK', 'data'=>$data];
        return Response::json($res);
    }
}
