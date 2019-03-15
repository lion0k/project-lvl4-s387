<?php

namespace SimpleTaskManager\Http\Controllers;

use SimpleTaskManager\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taskStatuses = TaskStatus::paginate(10);
        return view('taskstatuses.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('taskstatuses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:task_statuses,name'
        ]);

        $taskstatus = TaskStatus::create($request->all());
        flash(__('messages.taskstatuses.store', ['name' => $taskstatus->name]))->success();
        return redirect()->route('taskstatuses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \SimpleTaskManager\TaskStatus  $taskStatus
     * @return \Illuminate\Http\Response
     */
    public function show(TaskStatus $taskStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        return view('taskstatuses.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:task_statuses,name'
        ]);

        TaskStatus::findOrFail($id)->update($request->all());
        flash(__('messages.taskstatuses.update', ['name' => $request->name]))->success();
        return redirect()->route('taskstatuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TaskStatus::findOrFail($id)->delete();
        flash(__('messages.taskstatuses.destroy'))->success();
        return redirect()->route('taskstatuses.index');
    }
}
