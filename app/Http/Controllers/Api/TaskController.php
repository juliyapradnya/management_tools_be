<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Validator;
use App\Task;
use App\Project;
use App\Employee;
use App\Department;
use App\Ticket;
use App\User;
use App\Number;


class TaskController extends Controller
{
    public function index(){

        //$tasks = Task::all();
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','projects.project','employees.employee_name','departments.department_name')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    
    public function listDepedenceTask($id){
        $task = Task::find($id);
        $query_in = "";
        foreach ($task->task_depedence_id as $item) {
            if($query_in != ""){
                $query_in = $query_in.','.'"'.$item.'"';
            }else{
                $query_in = $query_in.'"'.$item.'"';
            }
        }

        if($query_in != ""){
            $listTask = Task::whereRaw('task_name in('.$query_in.')')->get();

            if(count($listTask) > 0 ){
                return response([
                    'message' => 'Retrieve All Success',
                    'data' => $listTask
                ],200);
            }
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function listTaskByYear($year){
        //$tasks = Task::all();
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->whereRaw('tasks.start_date >= "'.$year.'-01-01" and tasks.due_date <= "'.$year.'-12-31"')
                    ->select('tasks.*','projects.project_code','projects.project','employees.employee_name','departments.department_name')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function listTaskByYearUser($year){
        //$tasks = Task::all();
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->whereRaw('tasks.start_date >= "'.$year.'-01-01" and tasks.due_date <= "'.$year.'-12-31"')
                    ->select('tasks.*','projects.project_code','projects.project','employees.employee_name','departments.department_name')
                    ->whereRaw('employees.employee_name = "'.$user.'"')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function listTaskExcludeByTaskName($name){

        //$tasks = Task::all();
        
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','projects.project','employees.employee_name','departments.department_name')
                    ->whereRaw('task_name != "'.$name.'"')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function listTaskExcludeByTaskNameUser($name){

        //$tasks = Task::all();
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','projects.project','employees.employee_name','departments.department_name')
                    ->whereRaw('task_name != "'.$name.'"')
                    ->whereRaw('employees.employee_name = "'.$user.'"')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function indexReview(){

        //$tasks = Task::all();
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','employees.employee_name','departments.department_name')
                    ->whereRaw('status_task in ("reviewing", "completed")')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
        
    }

    public function indexReviewUser(){

        //$tasks = Task::all();
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','employees.employee_name','departments.department_name')
                    ->whereRaw('status_task in ("reviewing", "completed")')
                    ->whereRaw('employees.employee_name = "'.$user.'"')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
        
    }

    public function indexAssigneeUser(){

        //$tasks = Task::all();
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','employees.employee_name','departments.department_name')
                    ->whereRaw('status_task in ("in progress", "reviewing")')
                    ->whereRaw('employees.employee_name = "'.$user.'"')
                    ->get();
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
        
    }

    public function indexAssignee(){

        //$tasks = Task::all();
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','employees.employee_name','departments.department_name')
                    ->whereRaw('status_task in ("in progress", "reviewing")')
                    ->get();
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
        
    }

    public function listTaskTypeDepedence(){

        //$tasks = Task::all();
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','employees.employee_name','departments.department_name')
                    ->whereRaw('task_type = "dependence"')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
        
    }

    //tampilin daftar task milik siapa yang login di ticket
    public function listTaskUserLogin(){
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.task_name','employees.employee_name')
                    ->whereRaw('employees.employee_name = "'.$user.'"')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
        
    }

    public function showAssigneeTicket(){
        $tickets = DB::table('tasks')
                    ->join('tickets','tickets.id','=','tasks.id_ticket')
                    //->join('users','users.id','=','tickets.id_user')
                    ->select('tasks.task_name', 'tasks.status_task', 'tickets.detail_ticket','tickets.status_ticket')
                    ->whereRaw('status_task = "in progress"')
                    ->get();

        if(count($tickets) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tickets
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function showPieChartEvent(){
        $projects = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->select('tasks.status_task', 'projects.project')
                    ->get();

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

    public function showPieChartEmployee(){
        $employees = DB::table('tasks')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->select('tasks.status_task', 'employees.employee_name')
                    ->get();

        if(count($employees) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $employees
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function showTaskByEventFilterCalendar($id){
        $tasks = DB::table('tasks')
                 ->join('projects','projects.id','=','tasks.id_project')
                 ->select( 'projects.project', 'tasks.task_name', 'tasks.start_date', 'tasks.due_date')
                 ->whereRaw('projects.id = "'.$id.'"')
                 ->get();
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }

    public function showTaskByEventFilterCalendarUser($id){
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                 ->join('projects','projects.id','=','tasks.id_project')
                 ->join('employees','employees.id','=','tasks.id_employee')
                 ->select( 'projects.project', 'tasks.task_name', 'tasks.start_date', 'tasks.due_date', 'employees.employee_name')
                 ->whereRaw('projects.id = "'.$id.'"')
                 ->whereRaw('employees.employee_name = "'.$user.'"')
                 ->get();
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }

    public function showEmployeeByEventFilterCalendar($id){
        $tasks = DB::table('tasks')
                 ->join('projects','projects.id','=','tasks.id_project')
                 ->join('employees','employees.id','=','tasks.id_employee')
                 ->select( 'projects.project', 'employees.employee_name', 'tasks.start_date', 'tasks.due_date')
                 ->whereRaw('projects.id = "'.$id.'"')
                 ->get();
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }

    public function showEmployeeByEventFilterCalendarUser($id){
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                 ->join('projects','projects.id','=','tasks.id_project')
                 ->join('employees','employees.id','=','tasks.id_employee')
                 ->select( 'projects.project', 'employees.employee_name', 'tasks.start_date', 'tasks.due_date')
                 ->whereRaw('projects.id = "'.$id.'"')
                 ->whereRaw('employees.employee_name = "'.$user.'"')
                 ->get();
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }

    public function showStatusTaskByEventFilterCalendar($id){
        $tasks = DB::table('tasks')
                 ->join('projects','projects.id','=','tasks.id_project')
                 ->select( 'projects.project', 'tasks.task_name' ,'tasks.status_task', 'tasks.start_date', 'tasks.due_date')
                 ->whereRaw('projects.id = "'.$id.'"')
                 ->get();
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }

    public function showStatusTaskByEventFilterCalendarUser($id){
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                 ->join('projects','projects.id','=','tasks.id_project')
                 ->join('employees','employees.id','=','tasks.id_employee')
                 ->select( 'projects.project', 'tasks.task_name' ,'tasks.status_task', 'tasks.start_date', 'tasks.due_date','employees.employee_name')
                 ->whereRaw('projects.id = "'.$id.'"')
                 ->whereRaw('employees.employee_name = "'.$user.'"')
                 ->get();
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }


    public function showAssigneeView(){
     
        $tasks = Task::with('children')->select('id','task_name as name','status_task')
                ->whereRaw('status_task in ("in progress", "reviewing")')
                ->orderBy('task_name')
                ->get();
                 
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }

    public function showAssigneeViewUser(){
        //$user = auth()->user()->name;
        $tasks = Task::with('children')->select('id','task_name as name','status_task')
        //$tasks = DB::table('tasks')
                //->join('employees','employees.id','=','tasks.id_employee')
                ->whereRaw('status_task in ("in progress", "reviewing")')
                //->whereRaw('employees.employee_name = "'.$user.'"')
                ->orderBy('task_name')
                ->get();
                 
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }

    public function showReviewView(){
        $tasks = Task::with('children')->select('id','task_name as name','status_task')
                    ->whereRaw('status_task in ("reviewing", "completed")')->orderBy('task_name')->get();
                 
        
        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }
        
        return response([
            'message' => 'Empty',
            'data' => null
        ],400);

    }

    public function showAllEmployeeLogin(){
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->join('users','users.id','=','tasks.id_user')
                    ->select('tasks.*','projects.project_code','employees.employee_name','departments.department_name','users.name')
                    ->whereRaw('employees.employee_name = "'.$user.'"')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function showAllEmployeeLoginForCalendarEventUser(){
        $user = auth()->user()->name;
        $tasks = DB::table('tasks')
                    ->join('projects','projects.id','=','tasks.id_project')
                    ->join('employees','employees.id','=','tasks.id_employee')
                    ->join('departments','departments.id','=','tasks.id_department')
                    ->select('tasks.*','projects.project_code','employees.employee_name','departments.department_name','projects.project')
                    ->whereRaw('employees.employee_name = "'.$user.'"')
                    ->get();

        if(count($tasks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tasks
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }
    
    
    public function show($id){
        $task = Task::find($id);

        if(!is_null($task)){
            return response([
                'message' => 'Retrieve Task Success',
                'data' => $task
            ],200);
        }

        return response([
            'message' => 'Task Not Found',
            'data' => null
        ],400);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_project'       => 'required',
            'id_employee'      => 'required',
            'id_department'    => 'required',
            'id_ticket'        => 'nullable',
            'id_user'          => 'nullable',
            'task_name'        => 'required|unique:tasks',
            'task_id'          => 'nullable',
            'task_type'        => 'required|in:dependence,no',
            'task_depedence_id'=> 'nullable',
            'description'      => 'nullable',
            'start_date'       => 'required|date_format:Y-m-d',
            'due_date'         => 'required|date_format:Y-m-d',
            'status_task'      => 'required|in:open,in progress,reviewing,completed',
            
        ]);
        
        if($validate->fails())
            return response (['message' => $validate->errors()],400);

        $error = 0;
        
        if($request->task_type == 'dependence'){
            $task_depedence_id = explode(",",$request->task_depedence_id);
            $list=array(); 
            for($i = 0; $i<count($task_depedence_id); $i++) { 
                array_push($list, (Object)["name" => $task_depedence_id[$i]]);
            }
            
            $storeData['task_depedence_id'] = $task_depedence_id;
        }else{
            $task_depedence_id = array();
            $storeData['task_depedence_id'] = $task_depedence_id;
        }

        //$dependTask = Task::where('task_name', '=',$task->task_depedence_id)->first();
        if($request->due_date < $request->start_date){
            return response([
                'message' => 'Add task failed because due date less than start date',
                'data' => null
            ],400);
        }

        if($request->task_type == 'dependence'){
            foreach ($task_depedence_id as $dependence ) {
                error_log($dependence);
                $task_dependence = Task::whereRaw('task_name like "%'.$dependence.'%"')->first();
                if($task_dependence != null){
                    if($request->start_date > $task_dependence->due_date){
                        $error = 1;
                        return response([
                            'message' => 'Add task failed because start date more than due date task '.$task_dependence->task_name,
                            'data' => $task_dependence
                        ],400);
                        break;
                    }
                    else if($request->start_date > $task_dependence->start_date){
                        $error = 1;
                        return response([
                            'message' => 'Add task failed because start date more than start date task '.$task_dependence->task_name,
                            'data' => $task_dependence
                        ],400);
                        break;
                    }
                    else if($request->due_date < $task_dependence->due_date){
                        $error = 1;
                        return response([
                            'message' => 'Add task failed because due date less than due date task '.$task_dependence->task_name,
                            'data' => $task_dependence
                        ],400);
                        break;
                    }
                }
            }
            if($error != 1){
                $task = Task::create($storeData); 
            }
            
        }else{
            //$task->task_depedence_id = 'nothing';
            $task = Task::create($storeData);  
        }


        $department = Department::find($task->id_department);

        $todayDate = Carbon::now('Asia/Jakarta')->format('dmy');//untuk di no transaksi
        $hari_ini = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $temp = Task::where('start_date',$hari_ini)->get();
        
        $taskNumber = $temp->count() + 1;
        
        $task->task_id = $department->department_alias."-".$todayDate."-".$taskNumber;
        $task->save();

        if($task->save()){
            $lastNumber = Number::latest('num')->value('num');
            Number::insert([
                'num' => ($lastNumber + 1)
            ]);
            return response([
                'message' => 'Add Task Success',
                'data' => $task,
            ],200);
        }
    }

    public function destroy($id){
        $task = Task::find($id);

        if(is_null($task)){
            return response([
                'message' => 'Task Not Found',
                'data' => null
            ],404);
        }

        if($task->delete()){
            return response([
                'message' => 'Delete Task Success',
                'data' => $task,
            ],200);
        }
        
        return response([
            'message' => 'Delete Task Failed',
            'data' => null,
        ],400);

    }

    public function updateCopy(Request $request, $id){
        $task = Task::find($id);
        if(is_null($task)){
            return response([
                'message' => 'Task Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_project'       => 'required',
            'id_employee'      => 'required',
            'id_department'    => 'required',
            'id_ticket'        => 'nullable',
            'task_name'        => 'required',
            'task_id'          => 'nullable',
            'task_type'        => 'required|in:dependence,no',
            'task_depedence_id'=> 'nullable',
            'description'      => 'nullable',
            'start_date'       => 'required',
            'due_date'         => 'required',
            'status_task'      => 'required|in:open,in progress,reviewing,completed',
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        $task->id_project          = $updateData['id_project'];
        $task->id_employee         = $updateData['id_employee'];
        $task->id_department       = $updateData['id_department'];
        //$task->id_ticket         = $updateData['id_ticket'];
        $task->task_name           = $updateData['task_name'];
        //$task->task_id           = $updateData['task_id'];
        $task->task_type           = $updateData['task_type'];
        $task->task_depedence_id   = $updateData['task_depedence_id'];
        $task->description         = $updateData['description'];
        $task->start_date          = $updateData['start_date'];
        $task->due_date            = $updateData['due_date'];
        $task->status_task         = $updateData['status_task'];

        //$dependTask = Task::where('task_name', '=',$task->task_depedence_id)->first();

        if($task->save()){
            return response([
                'message' => 'Update Task Success',
                'data' => $task,
            ],200);
        } 

        return response([
            'message' => 'Update Task Failed',
            'data' => null
        ],400);
        
    }

    public function update(Request $request, $id){
        $task = Task::find($id);
        if(is_null($task)){
            return response([
                'message' => 'Task Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_project'       => 'required',
            'id_employee'      => 'required',
            'id_department'    => 'required',
            'id_ticket'        => 'nullable',
            'id_user'          => 'nullable',
            'task_name'        => 'required|unique:tasks,task_name,'.$id,
            'task_id'          => 'nullable',
            'task_type'        => 'required|in:dependence,no',
            'task_depedence_id'=> 'nullable',
            'description'      => 'nullable',
            'start_date'       => 'required',
            'due_date'         => 'required',
            'status_task'      => 'required|in:open,in progress,reviewing,completed',
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        $task->id_project          = $updateData['id_project'];
        $task->id_employee         = $updateData['id_employee'];
        $task->id_department       = $updateData['id_department'];
        //$task->id_ticket         = $updateData['id_ticket'];
        $task->task_name           = $updateData['task_name'];
        //$task->task_id           = $updateData['task_id'];
        $task->task_type           = $updateData['task_type'];
        $task->task_depedence_id   = $updateData['task_depedence_id'];
        $task->description         = $updateData['description'];
        $task->start_date          = $updateData['start_date'];
        $task->due_date            = $updateData['due_date'];
        $task->status_task         = $updateData['status_task'];

        //$dependTask = Task::where('task_name', '=',$task->task_depedence_id)->first();
        if($task->due_date < $task->start_date){
            return response([
                'message' => 'Update task failed because due date less than start date',
                'data' => null
            ],400);
        }

        if($task->task_type == 'dependence'){
            foreach ( $task->task_depedence_id as $dependence ) {
                error_log($dependence);
                $task_dependence = Task::whereRaw('task_name like "%'.$dependence.'%"')->first();
                if($task_dependence != null){
                    if($request->start_date > $task_dependence->due_date){
                        $error = 1;
                        return response([
                            'message' => 'Add task failed because start date more than due date task '.$task_dependence->task_name,
                            'data' => $task_dependence
                        ],400);
                        break;
                    }
                    else if($request->start_date > $task_dependence->start_date){
                        $error = 1;
                        return response([
                            'message' => 'Add task failed because start date more than start date task '.$task_dependence->task_name,
                            'data' => $task_dependence
                        ],400);
                        break;
                    }
                    else if($request->due_date < $task_dependence->due_date){
                        $error = 1;
                        return response([
                            'message' => 'Add task failed because due date less than due date task '.$task_dependence->task_name,
                            'data' => $task_dependence
                        ],400);
                        break;
                    }
                }
            }
        
            if($task->save()){
                return response([
                    'message' => 'Update Task Success',
                    'data' => $task,
                ],200);
            } 
        
        
        }else{
            
            if($task->save()){
                return response([
                    'message' => 'Update Task Success',
                    'data' => $task,
                ],200);
            } 
        }
        
        
    }

    public function updateStatus(Request $request, $id){
        $task = Task::find($id);        
        $jml_ticket_by_id_task = Ticket::whereRaw('id_task = ' .$id)->count();
        $closed_ticket = Ticket::whereRaw('id_task = ' .$id. ' and status_ticket = "closed"')->count();
        $ticket_number = Ticket::whereRaw('id_task = ' .$id. ' and status_ticket = "open"')->pluck('ticket_number');
        if(is_null($task)){
            return response([
                'message' => 'Task Status Not Found',
                'data' => null
            ],404); 
        } 

        //fumgsi untuk mengecek task yang sudah complete
        function countTaskComplete($task_depedence_id){
            $count = 0;
            for ($i = 0; $i<count($task_depedence_id); $i++) {
                $countCompleteTask = Task::whereRaw('task_name = "'.$task_depedence_id[$i].'" and status_task = "completed"')->count();
                if($countCompleteTask != null){
                      $count = $count + $countCompleteTask;
                }
                
            }
            return $count;
        }

        //fumgsi untuk mengecek task yang belum complete
        function countTaskNotComplete($task_depedence_id){
            $task_name="";
            foreach ($task_depedence_id as $depedence) {
                $task = Task::whereRaw('task_name = "'.$depedence.'" and status_task != "completed"')->value('task_name');
                if($task != null){
                      if($task_name != ""){
                        $task_name =$task_name . ", " . $task;
                      }else{
                        $task_name = $task_name . $task;
                      }
                }
                
            }
            return $task_name;
            
        }

        $count = countTaskComplete($task->task_depedence_id);
        $taskNotComplete = countTaskNotComplete($task->task_depedence_id);

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'status_task'      => 'required|in:open,in progress,reviewing,completed',
        ]); 

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        if($jml_ticket_by_id_task == $closed_ticket){           
            if($task->task_type == 'dependence'){
                if($count > 0 ){
                    if($count != count($task->task_depedence_id)){
                        return response([
                            'message' => 'Update Status Task Failed because '.$taskNotComplete.' not completed',
                            'data' => null
                        ],400);
                    }else{
                        $task->status_task    = $updateData['status_task'];
                
                        if($task->save()){
                            return response([
                                'message' => 'Update Status Task Success',
                                'data' => $task,
                            ],200);
                        }
                    }
                } else {
                    return response([
                        'message' => 'Update Status Task Failed because '.$taskNotComplete.' not completed or please check depedence task',
                        'data' => null
                    ],400);
                }
                
            }
            else{

                $taskWithSpecificDepedence = Task::whereRaw('task_depedence_id like "%'.$task->task_name.'%"')->get(); 
                
                if(count($taskWithSpecificDepedence) > 0){
                    if($updateData['status_task'] == 'in progress'){
                        Task::whereRaw('task_depedence_id like"%'.$task->task_name.'%"')
                              ->update(['status_task' => $updateData['status_task']]);
                    }
                }

                $task->status_task  = $updateData['status_task'];
                if($task->save()){
                    return response([
                        'message' => 'Update Status Task Success',
                        'data' => $task,
                    ],200);
                }
            } 
    
        } 
        else{
            if($task->status_task == 'open'){
                $task->status_task     = $updateData['status_task'];
                if($task->save()){
                    return response([
                        'message' => 'Update Status Task Success',
                        'data' => $task,
                    ],200);
                }
            }
            else {
                $message = '';
                for ($i=0; $i < count($ticket_number); $i++) { 
                    
                    if(count($ticket_number) - $i == 1){
                        $message  = $message .$ticket_number[$i];
                    }else{
                        $message = $message .$ticket_number[$i]. ', ';
                    }
                
            }
            
            return response([
                'message' => 'Update Status Task Failed because ticket number '.$message.' not closed',
                'data' => null
            ],400);
            }
            
        }
    }

    
}
