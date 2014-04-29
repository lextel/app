<?php

class AppsController extends \BaseController {

    /**
     * Display a listing of apps
     *
     * @return Response
     */
    public function index()
    {
        $apps = Apps::all();
        return View::make('apps.index', compact('apps'));
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
