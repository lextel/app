<?php

class AppsController extends \BaseController {

    /**
     * 匹配APP是否已经安装的列表
     *
     * @return Response
     */
    public function index($pn=0)
    {
	    $imei = '';
	    //$count = Apps::where('imei', '=', $imei)->count();
	    //检测是否已经安装了
        $logs = Applog::select('app_id')
            ->where('imei', '=', $imei)
            ->orwhere('status', '=', '0')
            ->get()->toArray();
	    //获得剩余的
	    $apps = Apps::select('id', 'package', 'title', 'icon', 'award', 'size', 'images', 'summary', 'link')
	            ->where('id', 'not in', $logs)
	            ->orwhere('is_delete', '=', '0')
	            ->get()->toArray();
	    foreach($apps as &$row){
	         $row['images'] = date("Y-m-d H:i", $row['images']);
	    }
	    $data = ['apps'=>$apps, 'count'=>0];
	    $res = ['code'=>0,'msg'=>'OK', 'data'=>$data];
	    return Response::json($res);
    }

    /**
     * Show the form for creating a new app
     *
     * @return Response
     */
    public function create()
    {
        return View::make('apps.create');
    }

    /**
     * Store a newly created app in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), App::$rules);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        App::create($data);

        return Redirect::route('apps.index');
    }

    /**
     * Display the specified app.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $app = App::findOrFail($id);

        return View::make('apps.show', compact('app'));
    }

    /**
     * Show the form for editing the specified app.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $app = App::find($id);

        return View::make('apps.edit', compact('app'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $app = App::findOrFail($id);

        $validator = Validator::make($data = Input::all(), App::$rules);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $app->update($data);

        return Redirect::route('apps.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        App::destroy($id);

        return Redirect::route('apps.index');
    }

}
