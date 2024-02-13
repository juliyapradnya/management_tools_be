<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use App\TaskDetail;
use App\Task;
use App\Subtask;


class TaskDetailController extends Controller
{
    public function index(){
        
        $task_detail = DB::table('task_details')
                    ->join('tasks','tasks.id','=','task_details.id_task')
                    ->join('subtasks','subtasks.id','=','task_details.id_subtask')
                    ->select('task_details.*','tasks.task_name','subtasks.subtask')
                    ->get();

        if(count($task_detail) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $task_detail
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function show($id){
        $task_detail = TaskDetail::find($id);

        if(!is_null($task_detail)){
            return response([
                'message' => 'Retrieve Task Detail Success',
                'data' => $task_detail
            ],200);
        }

        return response([
            'message' => 'Task Detail Not Found',
            'data' => null
        ],400);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_task' => 'required|numeric',
            'id_subtask' => 'required|numeric',
            'status' => 'required',
        ]);

        if($validate->fails())
            return response (['message' => $validate->errors()],400);

        $task_detail = TaskDetail::create($storeData);

        return response([
            'message' => 'Add Task Detail Success',
            'data' => $task_detail,
        ],200);
    }

    public function destroy($id){
        $task_detail = TaskDetail::find($id);

        if(is_null($task_detail)){
            return response([
                'message' => 'Task Detail Not Found',
                'data' => null
            ],404);
        }

        if($task_detail->delete()){
            return response([
                'message' => 'Delete Task Detail Success',
                'data' => $task_detail,
            ],200);
        }
        
        return response([
            'message' => 'Delete Task Detail Failed',
            'data' => null,
        ],400);

    }

    public function update(Request $request, $id){
        $task_detail = TaskDetail::find($id);
        if(is_null($task_detail)){
            return response([
                'message' => 'Task Detail Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_task' => 'required|numeric',
            'id_subtask' => 'required|numeric',
            'status' => 'required',
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        $task_detail->id_task    = $updateData['id_task'];
        $task_detail->id_subtask = $updateData['id_subtask'];
        $task_detail->status     = $updateData['status'];

        if($task_detail->save()){
            return response([
                'message' => 'Update Task Detail Success',
                'data' => $task_detail,
            ],200);
        }

        return response([
            'message' => 'Update Task Detail Failed',
            'data' => null
        ],400);
    }

    public function updateStatus(Request $request, $id){
        $task_detail = TaskDetail::find($id); 
        if(is_null($task_detail)){
            return response([
                'message' => 'Task Status Not Found',
                'data' => null
            ],404); 
        } 

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'status' => 'required',
        ]); 

        if($validate->fails())
        return response(['message' => $validate->errors()],400);
            
        $task_detail->status     = $updateData['status'];
        
        if($task_detail->save()){
            return response([
                'message' => 'Update Task Detail Success',
                'data' => $task_detail,
            ],200);
        }

        return response([
            'message' => 'Update Task Detail Failed',
            'data' => null
        ],400);
    
    }
    
}
