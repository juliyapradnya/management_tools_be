<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use App\Subtask;
use App\Project;
use App\Task;

class SubtaskController extends Controller
{
    public function index(){
        $subtask = Subtask::all();

        if(count($subtask) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $subtask
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id){
        $subtask = Subtask::find($id);

        if(!is_null($subtask)){
            return response([
                'message' => 'Retrieve Subtask Success',
                'data' => $subtask
            ],200);
        }

        return response([
            'message' => 'Subtask Not Found',
            'data' => null
        ],400);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'subtask' => 'required',
            'status_subtask' => 'required|in:in progress,done',
            'percentage_subtask' => 'nullable|numeric'
        ]);

        if($validate->fails())
            return response (['message' => $validate->errors()],400);

        $subtask = Subtask::create($storeData);
        return response([
            'message' => 'Add Subtask Success',
            'data' => $subtask,
        ],200);
    }

    public function destroy($id){
        $subtask = Subtask::find($id);

        if(is_null($subtask)){
            return response([
                'message' => 'Subtask Not Found',
                'data' => null
            ],404);
        }

        if($subtask->delete()){
            return response([
                'message' => 'Delete Subtask Success',
                'data' => $subtask,
            ],200);
        }
        
        return response([
            'message' => 'Delete Subtask Failed',
            'data' => null,
        ],400);

    }

    public function update(Request $request, $id){
        $subtask = Subtask::find($id);
        if(is_null($subtask)){
            return response([
                'message' => 'Subtask Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'subtask' => 'required',
            'status_subtask' => 'required|in:in progress,done',
            'percentage_subtask' => 'nullable|numeric'
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        $subtask->subtask             = $updateData['subtask'];
        $subtask->status_subtask      = $updateData['status_subtask'];
        $subtask->percentage_subtask  = $updateData['percentage_subtask'];
        
        
        if($subtask->save()){
            return response([
                'message' => 'Update Subtask Success',
                'data' => $subtask,
            ],200);
        }

        return response([
            'message' => 'Update Subtask Failed',
            'data' => null
        ],400);
    }

    

}
