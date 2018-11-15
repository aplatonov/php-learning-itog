<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Speciality;

class SpecialitiesController extends Controller
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
        $specialities =  Speciality::all();
        return view('specialities.index',['specialities' => $specialities]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin-control');
        return view('specialities.create');
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
            'name'=> 'required|max:60',
        ]);

        $form = $request->all();
        $form['active'] = isset($form['active']) ? 1 : 0;

        $speciality = Speciality::create($form);

        if($speciality) {
            return redirect()->route('specialities.index')->with(['message' => 'Специализация создана']);
        } else {
            return redirect()->route('specialities.index')->with(['message' => 'При сохранении специализации произошла ошибка']);
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
        $speciality = Speciality::findOrFail($id);
        return view('specialities.edit',compact('speciality'));
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
            'name'=> 'required|max:60',
        ]);

        $form = $request->all();
        $form['active'] = isset($form['active']) ? 1 : 0;

        $speciality = Speciality::findOrFail($id);
        $speciality->update($form);

        if($speciality) {
            return redirect()->route('specialities.index')->with(['message' => 'Специализация обновлена']);
        } else {
            return redirect()->route('specialities.index')->with(['message' => 'При обновлении специализации произошла ошибка']);
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
        $speciality = Speciality::findOrFail($id);
        try {
            $speciality->delete();
            return redirect()->route('specialities.index')->with(['message' => 'Специализация удалена']);
        } catch (Exception $e) {
            return redirect()->route('specialities.index')->with(['message' => 'При удалении специализации произошла ошибка']);
        }
    }
}
