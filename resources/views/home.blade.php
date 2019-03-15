@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <h4 class="card-title text-center">Hi {{ Auth::user()->name }}!</h4>
                    <hr>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="{{ route('tasks.index') }}">All tasks - {{ \SimpleTaskManager\Task::count() }}</a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('tasks.index', ['only_me' => 'on']) }}">Tasks created by you - {{ Auth::user()->createdTasks->count() }}</a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('tasks.index', ['assignedTo_id' =>  Auth::id() ]) }}">Tasks assigned to you - {{ Auth::user()->assignedTasks->count() }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
