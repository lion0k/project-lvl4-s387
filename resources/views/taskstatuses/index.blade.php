@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="display-6">Task status</h1>

        <div class="d-inline-block">
            @if (! $taskStatuses->isEmpty())
                <table class="table table-bordered table-white">
                    <thead>
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Updated_at</th>
                        <th scope="col">Created_at</th>
                        <th scope="col">Settings</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($taskStatuses as $taskstatus)
                        <tr>
                            <td class="vertical_align_table_content">{{ $taskstatus->id }}</td>
                            <td class="vertical_align_table_content">{{ $taskstatus->name }}</td>
                            <td class="vertical_align_table_content">{{ $taskstatus->updated_at }}</td>
                            <td class="vertical_align_table_content">{{ $taskstatus->created_at }}</td>
                            <td>
                                <div class="form-row">
                                    <form method="get" action="{{ route('taskstatuses.edit', ['id' => $taskstatus->id]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-info">Edit</button>
                                    </form>
                                    <form method="post" action="{{ route('taskstatuses.destroy', ['id' => $taskstatus->id]) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div>
                    <nav aria-label="Pages">
                        {{ $taskStatuses->links() }}
                    </nav>
                </div>
            @endif
        </div>

        <p class="my-3">
            <a href="{{ route('taskstatuses.create') }}" class="btn btn-primary">Add new Task Status</a>
        </p>
    </div>
@endsection