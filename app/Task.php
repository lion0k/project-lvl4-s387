<?php

namespace SimpleTaskManager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function scopeWithCreatedUser($query)
    {
        return $query->where('creator_id', Auth::id());
    }

    public function scopeWithStatus($query, $status_id)
    {
        return $query->where('status_id', $status_id);
    }

    public function scopeWithAssignedToUser($query, $user_id)
    {
        return $query->where('assignedTo_id', $user_id);
    }

    public function scopeWithTags($query, $tags)
    {
        return $query->whereHas('tags', function ($query) use ($tags) {
            $query->whereIn('id', $tags);
        });
    }
}
