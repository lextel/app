<?php

class ApplogsController extends \BaseController {

    /**
     * Display a listing of applogs
     *
     * @return Response
     */
    public function index()
    {
        $applogs = Applog::all();

        return View::make('applogs.index', compact('applogs'));
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

    /**
     * Display the specified applog.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $applog = Applog::findOrFail($id);

        return View::make('applogs.show', compact('applog'));
    }

    /**
     * Show the form for editing the specified applog.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $applog = Applog::find($id);

        return View::make('applogs.edit', compact('applog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $applog = Applog::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Applog::$rules);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $applog->update($data);

        return Redirect::route('applogs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Applog::destroy($id);

        return Redirect::route('applogs.index');
    }

}