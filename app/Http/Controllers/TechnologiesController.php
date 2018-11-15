<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Technology;

class TechnologiesController extends Controller
{
    /* Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin-control');
        $technologies =  Technology::all();
        return view('technologies.index',['technologies' => $technologies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin-control');
        return view('technologies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin-control');
        $this->validate($request,[
            'name'=> 'required|max:30',
        ]);

        $form = $request->all();
        $form['active'] = isset($form['active']) ? 1 : 0;

        $technology = Technology::create($form);

        if($technology) {
            return redirect()->route('technologies.index')->with(['message' => 'Технология создана']);
        } else {
            return redirect()->route('technologies.index')->with(['message' => 'При сохранении технологии произошла ошибка']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('admin-control');
        $technology = Technology::findOrFail($id);
        return view('technologies.edit',compact('technology'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('admin-control');
        $this->validate($request,[
            'name'=> 'required|max:30',
        ]);

        $form = $request->all();
        $form['active'] = isset($form['active']) ? 1 : 0;

        $technology = Technology::findOrFail($id);
        $technology->update($form);

        if($technology) {
            return redirect()->route('technologies.index')->with(['message' => 'Технология обновлена']);
        } else {
            return redirect()->route('technologies.index')->with(['message' => 'При обновлении технологии произошла ошибка']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('admin-control');
        $technology = Technology::findOrFail($id);
        try {
            $technology->delete();
            return redirect()->route('technologies.index')->with(['message' => 'Технология удалена']);
        } catch (Exception $e) {
            return redirect()->route('technologies.index')->with(['message' => 'При удалении технологии произошла ошибка']);
        }
    }
}
