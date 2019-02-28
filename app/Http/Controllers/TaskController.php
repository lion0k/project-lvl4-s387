<?php

namespace SimpleTaskManager\Http\Controllers;

use SimpleTaskManager\Task;
use Illuminate\Http\Request;
use SimpleTaskManager\User;
use SimpleTaskManager\TaskStatus;
use Illuminate\Support\Facades\Validator;
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
                'Not found this status. Possible status has been deleted.'
            );
        }

        if ($assignedToId) {
            $validator->errors()->add(
                'assignedTo_id',
                'There is no such user. Possible user has been deleted.'
            );
        }

        return $validator;
    }

    protected function prepareTags($tags):array
    {
        if (empty(trim($tags))) {
            return [];
        }

        $inputTags = collect(explode(',', $tags));
        return $inputTags->map(function ($tag) {
            return strtolower(trim($tag));
        })->reject(function ($tag) {
            return empty($tag);
        })->unique()
          ->All();
    }

    protected function saveTags(array $tags, Task $task)
    {
        foreach ($tags as $tag) {
            $tag = Tag::firstOrCreate(['name' => $tag]);
            $task->tags()->attach($tag->id);
        }
    }

    protected function checkTrashedUsers(Request $request)
    {
        $assignedUser = User::withTrashed()->find($request->assignedTo_id);

        if ($assignedUser->trashed()) {
            flash('User, on which assigned task, deleted. Please choose another one!')
                ->error()
                ->important();

            return false;
        }

        return true;
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

        if ($this->checkTrashedUsers($request)) {
            $task = new Task();
            $task->name = $request->name;
            $task->description = $request->description;
            $task->status_id = $request->status_id;
            $task->assignedTo_id = $request->assignedTo_id;
            $task->creator_id = $user->id;

            $task->save();

            $prepareTags = $this->prepareTags($request->tags);
            $this->saveTags($prepareTags, $task);

            flash("Task&nbsp; \"$task->name\" &nbsp;has been successfully created!");
        }

        return redirect()->route('task.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $user = Auth::user();

        $validator = $this->getValidation($request);
        if ($validator->fails()) {
            return redirect()->route('task.edit')
                ->withErrors($validator)
                ->withInput();
        }

        if ($this->checkTrashedUsers($request)) {
            $task->name = $request->name;
            $task->description = $request->description;
            $task->status_id = $request->status_id;



            $task->assignedTo_id = $request->assignedTo_id;
            $task->creator_id = $user->id;
            $task->save();

            $task->tags()->detach();
            $prepareTags = $this->prepareTags($request->tags);
            $this->saveTags($prepareTags, $task);

            flash("Successfully updated '{$task->name}' task")->success();
        }

        return redirect()->route('task.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \SimpleTaskManager\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->tags()->detach();
        Task::findOrFail($task->id)->delete();
        flash('The task status has been successfully deleted')->success()->important();
        return redirect()->route('task.index');
    }
}
