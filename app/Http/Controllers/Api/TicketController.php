<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use App\Ticket;
use App\Task;
use App\User;

class TicketController extends Controller
{
    public function index(){
        $tickets = DB::table('tickets')
                    ->join('tasks','tasks.id','=','tickets.id_task')
                    ->join('users','users.id','=','tickets.id_user')
                    ->select('tickets.*','tasks.task_name', 'tasks.task_id', 'users.name')
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

    public function indexTicketUser(){
        $user = auth()->user()->name;
        $tickets = DB::table('tickets')
                    ->join('tasks','tasks.id','=','tickets.id_task')
                    ->join('users','users.id','=','tickets.id_user')
                    ->select('tickets.*','tasks.task_name', 'tasks.task_id', 'users.name')
                    ->whereRaw('users.name = "'.$user.'"')
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

    public function showTicket($id){
        $tickets = DB::table('tickets')
                    ->join('tasks','tasks.id','=','tickets.id_task')
                    ->join('users','users.id','=','tickets.id_user')
                    ->select('tickets.*', 'users.name')
                    ->where('tickets.id_task','=',$id)
                    ->get($id);

        if(!is_null($tickets)){
            return response([
                    'message' => 'Retrieve Ticket Success',
                    'data' => $tickets
            ],200);
        }
            
        return response([
                'message'=>'Ticket Not Found',
                'data' => null
            ],404);
    }

    public function show($id){
        $ticket = Ticket::find($id);

        if(!is_null($ticket)){
            return response([
                'message' => 'Retrieve Ticket Success',
                'data' => $ticket
            ],200);
        }

        return response([
            'message' => 'Ticket Not Found',
            'data' => null
        ],400);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_task'       => 'required',
            'id_user'       => 'required',
            'ticket_number' => 'nullable',
            'from_ticket'   => 'nullable',
            'to_ticket'     => 'nullable',
            'detail_ticket' => 'required',
            'status_ticket' => 'nullable|in:open,closed',
            
        ]);

        if($validate->fails())
            return response (['message' => $validate->errors()],400);

        $task = Task::find($request->id_task);

        if($task->status_task != 'completed'){
            $ticket = Ticket::create($storeData);

            $todayDate = Carbon::now('Asia/Jakarta')->format('dmy');//untuk di no transaksi
            $hari_ini = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $temp = Ticket::where('created_at',$hari_ini)->get();
            
            $number = $temp->count() + 1;

            $ticket->created_at = $hari_ini;
            $ticket->ticket_number = "TIC"."-".$todayDate."-".$number;
            $ticket->save();

            return response([
                'message' => 'Add Ticket Success',
                'data' => $ticket,
            ],200);

        }else{
            return response([
                'message' => 'Create ticket failed because task ' .$task->task_name. ' is completed',
            ],400);
        }
    }

    public function destroy($id){
        $ticket = Ticket::find($id);

        if(is_null($ticket)){
            return response([
                'message' => 'Ticket Not Found',
                'data' => null
            ],404);
        }

        if($ticket->delete()){
            return response([
                'message' => 'Delete Ticket Success',
                'data' => $ticket,
            ],200);
        }
        
        return response([
            'message' => 'Delete Ticket Failed',
            'data' => null,
        ],400);

    }

    public function update(Request $request, $id){
        $ticket = Ticket::find($id);
        if(is_null($ticket)){
            return response([
                'message' => 'Ticket Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_task'           => 'required',
            'id_user'           => 'required',
            'ticket_number'     => 'nullable',
            'from_ticket'       => 'nullable',
            'to_ticket'         => 'nullable',
            'detail_ticket'     => 'required',
            'status_ticket'     => 'nullable|in:open,closed',
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        $ticket->id_task            = $updateData['id_task'];
        $ticket->id_user            = $updateData['id_user'];
        //$ticket->ticket_number      = $updateData['ticket_number'];
        //$ticket->from_ticket        = $updateData['from_ticket'];
        //$ticket->to_ticket          = $updateData['to_ticket'];
        $ticket->detail_ticket      = $updateData['detail_ticket'];
        $ticket->status_ticket      = $updateData['status_ticket'];

        $task = Task::find($ticket->id_task);

        if($task->status_task == 'open'){

            $todayDate = Carbon::now('Asia/Jakarta')->format('dmy');//untuk di no transaksi
            $hari_ini = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $temp = Ticket::where('created_at',$hari_ini)->get();
            
            $number = $temp->count() + 1;

            $ticket->created_at = $hari_ini;
            $ticket->ticket_number = "TIC"."-".$todayDate."-".$number;
            if($ticket->save()){
                return response([
                    'message' => 'Update Ticket Success',
                    'data' => $ticket,
                ],200);
            }

        }else{
            return response([
                'message' => 'update ticket failed because task ' .$task->task_name. ' is not open',
            ],400);
        }
        
    }

    public function updateStatusTicket(Request $request, $id){
        $ticket = Ticket::find($id);
        if(is_null($ticket)){
            return response([
                'message' => 'Ticket Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'status_ticket' => 'nullable|in:open,closed',
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        $ticket->status_ticket      = $updateData['status_ticket'];
        
        if($ticket->save()){
            return response([
                'message' => 'Update Ticket Success',
                'data' => $ticket,
            ],200);
        }

        return response([
            'message' => 'Update Ticket Failed',
            'data' => null
        ],400);
    }
    
}
