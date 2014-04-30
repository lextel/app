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
        $installedApps = trim(Input::get('installapps', ''));
	    //$count = Apps::where('imei', '=', $imei)->count();
	    $installedApps = explode(",", $installedApps);
	    //检测是否已经安装了
        $logs = Applog::select('app_id')
            ->where('imei', '=', $imei)
            ->where('status', '=', '0')
            ->get()->toArray();
            
	    //获得剩余的
	    if (empty($installedApps)) $installedApps = [];
	    $logs = array_unique(array_merge($logs, $installedApps));
	    $apps = [];
	    if (!empty($logs)){
	        $apps = Apps::select('id', 'package', 'title', 'icon', 'award', 'size', 'images', 'summary', 'link')
	                ->whereNotIn('id', $logs)
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
