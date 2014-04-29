<?php

class ApplogsController extends \BaseController {
    private $page_size = 10;
    /**
     * 用户APP积分日志列表，根据用户ID获取
     *
     * @return Response
     */
    public function index($pn = 0)
    {
        if (! Auth::check()){
	        $res = ['code'=>1, 'msg'=>'请登陆'];
            return Response::json($res);
	    }
	    $user = Auth::getUser();
	    $userId = $user->id;
	    $count = Applogs::where('member_id', '=', $userId)->count();
	    $logs = [];
	    if ($count > $pn){
	        $logs = Applogs::select('id', 'app_id', 'award', 'created_at')
	            ->where('member_id', '=',$userId)
	            ->orderBy('id', 'desc')
	            ->take($this->page_size)
	            ->skip($pn)
	            ->get()->toArray();
	    }
	    
	    foreach($logs as &$row){
	         $row['created_at'] = date("Y-m-d H:i", $row['created_at']);
	    }
	    $data = ['logs'=>$logs, 'count'=>$count];
	    $res = ['code'=>0,'msg'=>'OK', 'data'=>$data];
	    return Response::json($res);
    }

    /**
     * Show the form for creating a new applog
     *
     * @return Response
     */
    public function create()
    {
        return View::make('applogs.create');
    }

    /**
     * Store a newly created applog in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), Applog::$rules);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Applog::create($data);

        return Redirect::route('applogs.index');
    }
    
    

}
