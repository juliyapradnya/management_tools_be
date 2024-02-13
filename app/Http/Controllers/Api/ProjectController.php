<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use App\Project;


class ProjectController extends Controller
{
    public function index(){
        $projects = Project::all();

        if(count($projects) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $projects
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id){
        $project = Project::find($id);

        if(!is_null($project)){
            return response([
                'message' => 'Retrieve Project Success',
                'data' => $project
            ],200);
        }

        return response([
            'message' => 'Project Not Found',
            'data' => null
        ],400);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'project_code'  => 'required',
            'project'       => 'required',
            
        ]);

        if($validate->fails())
            return response (['message' => $validate->errors()],400);

        $project = Project::create($storeData);
        return response([
            'message' => 'Add Project Success',
            'data' => $project,
        ],200);
    }

    public function destroy($id){
        $project = Project::find($id);

        if(is_null($project)){
            return response([
                'message' => 'Project Not Found',
                'data' => null
            ],404);
        }

        if($project->delete()){
            return response([
                'message' => 'Delete Project Success',
                'data' => $project,
            ],200);
        }
        
        return response([
            'message' => 'Delete Project Failed',
            'data' => null,
        ],400);

    }

    public function update(Request $request, $id){
        $project = Project::find($id);
        if(is_null($project)){
            return response([
                'message' => 'Project Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'project_code'  => 'required',
            'project'    => 'required',
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        $project->project_code    = $updateData['project_code'];
        $project->project         = $updateData['project'];
        

        if($project->save()){
            return response([
                'message' => 'Update Project Success',
                'data' => $project,
            ],200);
        }

        return response([
            'message' => 'Update Project Failed',
            'data' => null
        ],400);
    }
    
}
