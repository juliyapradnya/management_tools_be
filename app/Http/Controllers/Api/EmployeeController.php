<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Employee;
use App\Department;
use App\User;

class EmployeeController extends Controller
{
    public function index(){
        //$employees = Employee::all();

        $employees = DB::table('employees')
                    ->join('departments','departments.id','=','employees.id_department')
                    ->select('employees.*','departments.department_name')
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

    public function showEmployeeLogin(){
        $user = auth()->user()->name;
        $employees = DB::table('employees')
                    ->whereRaw('employee_name = "'.$user.'"')
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

    public function show($id){
        $employee = Employee::find($id);

        if(!is_null($employee)){
            return response([
                'message' => 'Retrieve Employee Success',
                'data' => $employee
            ],200);
        }

        return response([
            'message' => 'Employee Not Found',
            'data' => null
        ],400);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [ 
            'employee_name' => 'required',
            'id_department' => 'required',
        ]);

        if($validate->fails())
            return response (['message' => $validate->errors()],400);

       $employee = Employee::create($storeData);
        return response([
            'message' => 'Add Employee Success',
            'data' =>$employee,
        ],200);
    }

    public function destroy($id){
        $employee = Employee::find($id);

        if(is_null($employee)){
            return response([
                'message' => 'Employee Not Found',
                'data' => null
            ],404);
        }

        if($employee->delete()){
            return response([
                'message' => 'Delete Employee Success',
                'data' => $employee,
            ],200);
        }
        
        return response([
            'message' => 'Delete Employee Failed',
            'data' => null,
        ],400);

    }

    public function update(Request $request, $id){
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response([
                'message' => 'Employee Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            
            'employee_name' => 'required',
            'id_department' => 'required',
            
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

       
        $employee->employee_name = $updateData['employee_name'];
        $employee->id_department = $updateData['id_department'];
        
        

        if($employee->save()){
            return response([
                'message' => 'Update Employee Success',
                'data' => $employee,
            ],200);
        }

        return response([
            'message' => 'Update Employee Failed',
            'data' => null
        ],400);
    }
    
}
