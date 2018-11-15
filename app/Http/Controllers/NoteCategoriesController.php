<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\NotesCategory;

class NoteCategoriesController extends Controller
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
        $notecategories =  NotesCategory::all();
        return view('notecategories.index',['notecategories' => $notecategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('admin-control');
        return view('notecategories.create');
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

        $notecategory = NotesCategory::create($form);

        if($notecategory) {
            return redirect()->route('notecategories.index')->with(['message' => 'Тип сообщения создан']);
        } else {
            return redirect()->route('notecategories.index')->with(['message' => 'При сохранении типа сообщения произошла ошибка']);
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
        $notecategory = NotesCategory::findOrFail($id);
        return view('notecategories.edit',compact('notecategory'));
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
        //dd($form);

        $notecategory = NotesCategory::findOrFail($id);
        $notecategory->update($form);

        if($notecategory) {
            return redirect()->route('notecategories.index')->with(['message' => 'Тип сообщения обновлен']);
        } else {
            return redirect()->route('notecategories.index')->with(['message' => 'При обновлении типа сообщения произошла ошибка']);
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
        $notecategory = NotesCategory::findOrFail($id);
        try {
            $notecategory->delete();
            return redirect()->route('notecategories.index')->with(['message' => 'Тип сообщения удален']);
        } catch (Exception $e) {
            return redirect()->route('notecategories.index')->with(['message' => 'При удалении типа сообщения произошла ошибка']);
        }
    }
}
