<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    protected $casts = [
        'task_depedence_id' => 'array', // Will convarted to (Array)
    ];
    
    protected $fillable = [
        'id_project', 'id_employee', 'id_department', 'id_ticket', 'task_name', 'task_id', 'task_type', 'task_depedence_id', 'description', 'start_date', 'due_date', 'status_task'
    ];

    /**
     * Set the task depedence
     *
     */
    // public function setTaskDepedenceIdAttribute($value)
    // {
    //     $this->attributes['task_depedence_id'] = implode(',',$value);
    // }
  
    /**
     * Get the task depedence
     *
     */
    // public function getTaskDepedenceIdAttribute($value)
    // {
    //     return explode(',',$value);
    // }

    public function children()
    {
        return $this->hasMany('App\Ticket', 'id_task', 'id')->select(['id', DB::raw("CONCAT(ticket_number,' - ',status_ticket) AS name"),'id_task']);
    }

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
