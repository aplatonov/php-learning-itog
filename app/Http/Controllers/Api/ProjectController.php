<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Projects;
use Validator;
use Illuminate\Support\Facades\Auth;

class ProjectController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = null;
        if (Auth::user()->isAdmin()) {
            $projects = Projects::all();
        } elseif (Auth::user()->isValidUser()) {
            $projects = Projects::where('active', 1)->get();
        }

        return $projects ?
            $this->sendResponse($projects->toArray(), 'Projects retrieved successfully.') :
            $this->sendError('Projects not found or no access rights.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = null;
        if (Auth::user()->isAdmin() || Auth::user()->isValidUser()) {
            $project = Projects::find($id)->with('projectMarks')->with('projectTechnologies')->get();
        }

        if (!$project) {
            return $this->sendError('Project not found.');
        }

        return $this->sendResponse($project->toArray(), 'Project retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        $this->validate($request, [
            'project_name' => 'required|min:2|max:150',
            'description' => 'required',
            'speciality_id' => 'required|integer|min:1',
            'doc' => 'file|max:1000|mimes:pdf,doc,docx,rtf',
            'start_date' => 'date|nullable',
            'finish_date' => 'date|nullable',
            'technologies' => 'present'
        ], [
            'project_name.required' => 'Название проекта обязательно к заполненнию',
            'speciality_id.min' => 'Необходимо выбрать специализацию',
            'technologies.present' => 'Укажите требуемые технологии проекта'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($input);

        return $this->sendResponse($product->toArray(), 'Product created successfully.');*/
    }
}
