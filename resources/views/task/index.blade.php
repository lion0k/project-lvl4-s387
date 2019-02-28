@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="display-6">Tasks</h1>
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
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->creator->name ?? ''  }}</td>
                            <td>{{ $task->assignedTo->name ?? ''  }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->status->name ?? ''  }}</td>
                            <td>{{ $task->tags->pluck('name')->implode(', ') }}</td>
                            <td>
                                <div class="form-row">
                                    <form method="get" action="{{ route('task.edit', ['id' => $task->id]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-info btn-margin-left15">Edit</button>
                                    </form>
                                    <form method="post" action="{{ route('task.destroy', ['id' => $task->id]) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-margin-left15 btn-margin-rgt15" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
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

        <p class="my-3">
            <a href="{{ route('task.create') }}" class="btn btn-primary">Add new task</a>
        </p>
    </div>
@endsection