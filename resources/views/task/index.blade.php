@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="display-6">Tasks</h1>
        <div class="card-body">
            <form class="mb-3" action="{{ route('tasks.index') }}" method="GET">
                <div class="form-row">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">Filter</h6>
                            <hr>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-xs-4 p-1">
                                                <label for="assignedTo_id">Assigned by user</label>
                                                <select id="assignedTo_id" class="custom-select custom-select-sm" name="assignedTo_id">
                                                    <option value="" {{ Request::get('assignedTo_id') ? '' : 'selected' }}>---</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" {{ Request::get('assignedTo_id') == $user->id ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xs-4 p-1">
                                                <label for="status_id">Task status</label>
                                                <select id="status_id" class="custom-select custom-select-sm" name="status_id">
                                                    <option value="" {{ Request::get('status_id') ? '' : 'selected' }}>---</option>
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status->id }}" {{ Request::get('status_id') == $status->id ? 'selected' : '' }}>
                                                            {{ $status->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xs-4 p-1">
                                                <label for="tag_id">Tag</label>
                                                <select id="tag_id" class="custom-select custom-select-sm" name="tag_id">
                                                    <option value="" {{ Request::get('tag_id') ? '' : 'selected' }}>---</option>
                                                    @foreach ($tags as $tag)
                                                        <option value="{{ $tag->id }}" {{ Request::get('tag_id') == $tag->id ? 'selected' : '' }}>
                                                            {{ $tag->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-1">
                                    <label for="only_me">Tasks created by me</label>
                                    <input id="only_me" type="checkbox" name="only_me" {{ Request::get('only_me') ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-xs-6 p-2">
                                                <button type="submit" class="btn btn-sm btn-outline-success">Search</button>
                                            </div>
                                            <div class="col-xs-6 p-2">
                                                <a class="btn btn-sm btn-outline-info" href="{{ route('tasks.index') }}">Remove filter</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="d-inline-block">
                @if (! $tasks->isEmpty())
                    <table class="table table-bordered table-white table-sm">
                        <thead class="thead-dark">
                        <tr class="text-center">
                            <th scope="col">Name</th>
                            <th scope="col">Creator</th>
                            <th scope="col">Assigned to</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tags</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="vertical_align_table_content">{{ $task->name }}</td>
                                <td class="vertical_align_table_content">{{ $task->creator->name ?? ''  }}</td>
                                <td class="vertical_align_table_content">{{ $task->assignedTo->name ?? ''  }}</td>
                                <td class="vertical_align_table_content">{{ $task->description }}</td>
                                <td class="vertical_align_table_content">{{ $task->status->name ?? ''  }}</td>
                                <td class="vertical_align_table_content">{{ $task->tags->pluck('name')->implode(', ') }}</td>
                                <td>
                                    <div class="container">
                                        <div class="row no-gutters">
                                            <div class="col-6">
                                                <a href="{{ route('tasks.edit', ['id' => $task->id]) }}" class="btn btn-sm btn-outline-info"
                                                   rel="nofollow">Edit</a>
                                            </div>
                                            <div class="col-6">
                                                <a href="{{ route('tasks.destroy', ['id' => $task->id]) }}" class="btn btn-sm btn-outline-danger"
                                                   data-method="delete"
                                                   data-confirm="Are you sure you want to delete this task?" rel="nofollow">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        <nav aria-label="Pages">
                            {{ $tasks->links() }}
                        </nav>
                    </div>
                @else
                    <p>
                        No tasks found.
                    </p>
                @endif
            </div>

            <p class="my-1">
                <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary">Add new task</a>
            </p>
        </div>
    </div>
@endsection