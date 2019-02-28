<?php

namespace SimpleTaskManager\Http\Controllers;

use SimpleTaskManager\Task;
use Illuminate\Http\Request;
use SimpleTaskManager\User;
use SimpleTaskManager\TaskStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use SimpleTaskManager\Tag;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only('create', 'store');
    }

    protected function getValidation(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'description' => 'string|max:512',
                'status_id' => 'required|integer',
                'assignedTo_id' => 'required|integer'
            ]
        );

        $statusId = TaskStatus::find($request->status_id);
        $assignedToId = User::find($request->assignedTo_id);

        if (!$statusId) {
            $validator->errors()->add(
                'status_id',
                'Not found this status. Possible it has been deleted.'
            );
        }

        if ($assignedToId) {
            $validator->errors()->add(
                'assignedTo_id',
                'There is no such user. May be user has been deleted. Choose user again.'
            );
        }

        return $validator;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::paginate(10);
        return view('task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('task.create', [
                'users' => User::all(),
                'taskstatuses' => TaskStatus::all()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = $this->getValidation($request);
        if ($validator->fails()) {
            return redirect()->route('task.create')
                ->withErrors($validator)
                ->withInput();
        }

        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->assignedTo_id = $request->assignedTo_id;
        $task->creator_id = $user->id;

        $task->save();

        $inputTags = collect(explode(',', $request->tags));
        $prepareTags = $inputTags->map(function ($tag) {
            return strtolower(trim($tag));
        })->reject(function ($tag) {
                return empty($tag);
        })->unique();

        foreach ($prepareTags as $tag) {
            $tag = Tag::firstOrCreate(['name' => $tag]);
            $task->tags()->attach($tag->id);
        }

        flash("Task&nbsp; \"$task->name\" &nbsp;has been successfully created!");
        return redirect()->route('task.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \SimpleTaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \SimpleTaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $getTagsNames = $task->tags->map(function ($tag) {
            return $tag->name;
        })->all();
        $tagsNamesStr = implode(', ', $getTagsNames);

        return view('task.edit', [
            'users' => User::all(),
            'taskstatuses' => TaskStatus::all(),
            'task' => $task,
            'tagsNamesStr' => $tagsNamesStr
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \SimpleTaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \SimpleTaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
