<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model
{
    protected $fillable = [
        'id_task', 'id_user', 'ticket_number', 'from_ticket', 'to_ticket', 'detail_ticket', 'status_ticket'
    ];

    public function task()
    {
        return $this->belongsTo('App\Task', 'id_task', 'id');
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
