<?php

namespace App\Http\Controllers\Api;

use App\ProjectMark;
use App\ProjectsHasTechnology;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Projects;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


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
            $project = Projects::find($id)->with(['projectMarks', 'projectTechnologies'])->get();
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'project_name' => 'required|min:2|max:150',
            'description' => 'required',
            'speciality_id' => 'required|integer|min:1',
            'doc' => 'file|max:1000|mimes:pdf,doc,docx,rtf',
            'start_date' => 'date|nullable',
            'finish_date' => 'date|nullable',
        ], [
            'project_name.required' => 'Название проекта обязательно к заполненнию',
            'speciality_id.min' => 'Необходимо выбрать специализацию',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['owner_id'] = Auth::user()->id;
        $input['active'] = isset($input['active']) ? 1 : 0;

        $project = Projects::create($input);

        if ($project) {
            if (isset($input['technologies'])) {
                $technologies = json_decode($input['technologies'], true);
                foreach ($technologies as $technology_id => $technology_name) {
                    ProjectsHasTechnology::create([
                        'project_id' => $project->id,
                        'technology_id' => $technology_id
                    ]);
                }
            }

            if (isset($input['project_marks'])) {
                $project_marks = json_decode($input['project_marks'], true);
                foreach ($project_marks as $mark) {
                    // сохраняем только не пустые пункты
                    if (!empty($mark['name'])) {
                        // если дата в неправильном формате присваиваем ей значение даты финиша проекта
                        $return_date = $mark['finish_date'];
                        $d = \DateTime::createFromFormat('Y-m-d', $return_date);
                        if (!$d || !$d->format('Y-m-d') == $return_date) {
                            $return_date = $project->finish_date;
                        }
                        ProjectMark::create([
                            'project_id' => $project->id,
                            'name' => $mark['name'],
                            'finish_date' => $return_date,
                            'is_done' => (isset($mark['is_done']) && $mark['is_done'] == 1) ? 1 : 0
                        ]);
                    }
                }
            }

            return $this->sendResponse($project->toArray(), 'Project created successfully.');
        } else {
            return $this->sendError('Store Error.');
        }
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
        //TODO доделать метод

        $input = $request->all();
        $project = Projects::findOrFail($id);

        // допустим админ или хозяин

        $validator = Validator::make($input, [
            //...
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $project->name = $input['name'];
        //...
        $project->save();

        return $this->sendResponse($project->toArray(), 'Project updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Projects::findOrFail($id);
        $projectName = [];
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->id == $project->owner_id)) {
            $projectName[] = '#'.$project->id.' '.$project->project_name;
            try {
                DB::beginTransaction();
                ProjectsHasTechnology::where('project_id', $project->id)->delete();
                ProjectMark::where('project_id', $project->id)->delete();
                Storage::delete($project->doc);
                $project->delete();
                DB::commit();
                return $this->sendResponse($projectName, 'Project deleted successfully.', 204);
            } catch (Exception $e) {
                DB::rollBack();
                return $this->sendError('Delete Error. ' . $projectName);
            }
        } else {
            return $this->sendError('Delete Error. Unauthorized.');
        }
    }
}
