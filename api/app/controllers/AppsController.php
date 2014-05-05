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
        if (empty($imei)){
            $res = ['code'=>1, 'msg'=>'非手机平台登录，不能操作'];
            return Response::json($res);
        }
        $installedApps = Input::get('apps', []);
        if (!is_array($installedApps)){
             $res = ['code'=>1, 'msg'=>'apps type must is array'];
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
        if (!empty($logs)){
            $apps = Apps::select('id', 'package', 'title', 'icon', 'award', 'size', 'images', 'summary', 'link')
                    ->whereNotIn('package', $logs)
                    ->where('is_delete', '=', '0')
                    ->get()->toArray();
            foreach($apps as &$row){
                 $row['images'] = unserialize($row['images']);
            }
        }
        $data = ['apps'=>$apps, 'count'=>count($apps)];
        $res = ['code'=>0,'msg'=>'OK', 'data'=>$data];
        return Response::json($res);
    }
}
