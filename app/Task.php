<?php

namespace SimpleTaskManager;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Input;

class Task extends Model
{
    protected $fillable = [
        'name', 'description', 'status_id', 'creator_id', 'assignedTo_id'
    ];

    public function creator()
    {
        return $this->belongsTo('SimpleTaskManager\User', 'creator_id', 'id')
            ->withTrashed();
    }

    public function assignedTo()
    {
        return $this->belongsTo('SimpleTaskManager\User', 'assignedTo_id', 'id')
            ->withTrashed();
    }

    public function status()
    {
        return $this->belongsTo('SimpleTaskManager\TaskStatus', 'status_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany('SimpleTaskManager\Tag', 'task_tag');
    }

    public static function filterTasks()
    {
        return Task::with(['creator', 'assignedTo', 'status', 'tags'])
            ->withCreatedUser(Input::get('only_me'))
            ->withAssignedToUser(Input::get('assignedTo_id'))
            ->withStatus(Input::get('status_id'))
            ->withTags(Input::get('tag_id'));
    }

    public function scopeWithTags($query, $tag_id)
    {
        if ($tag_id) {
            return $query->whereHas('tags', function ($query) use ($tag_id) {
                $query->where('tag_id', $tag_id);
            });
        }
        return $query;
    }

    public function scopeWithCreatedUser($query, $field)
    {
        if ($field) {
            return $query->where('creator_id', Auth::id());
        }
        return $query;
    }

    public function scopeWithStatus($query, $status_id)
    {
        if ($status_id) {
            return $query->where('status_id', $status_id);
        }
        return $query;
    }

    public function scopeWithAssignedToUser($query, $user_id)
    {
        if ($user_id) {
            return $query->where('assignedTo_id', $user_id);
        }
        return $query;
    }
}
