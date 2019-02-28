<?php

namespace SimpleTaskManager;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name', 'description', 'status_id', 'creator_id', 'assignedTo_id'
    ];

    public function creator()
    {
        return $this->belongsTo('SimpleTaskManager\User', 'creator_id', 'id');
    }

    public function assignedTo()
    {
        return $this->belongsTo('SimpleTaskManager\User', 'assignedTo_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo('SimpleTaskManager\TaskStatus', 'status_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany('SimpleTaskManager\Tag', 'task_tag');
    }
}
