<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Projects;
use Validator;

class ProjectController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Projects::all();

        return $this->sendResponse($projects->toArray(), 'Projects retrieved successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Projects::find($id);

        if (is_null($project)) {
            return $this->sendError('Project not found.');
        }

        return $this->sendResponse($project->toArray(), 'Project retrieved successfully.');
    }
}
