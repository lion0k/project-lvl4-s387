<?php

namespace SimpleTaskManager;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name', 'description', 'status_id', 'creator_id', 'assignedTo_id'
    ];
}
