@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="display-6">Task status</h1>
        <div class="d-inline-block">
            @if (! $taskStatuses->isEmpty())
                <table class="table table-bordered table-white table-sm">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Updated_at</th>
                        <th scope="col">Created_at</th>
                        <th scope="col">Actions</th>
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
                                        <button type="submit" class="btn btn-sm btn-outline-info btn-margin-left15">Edit</button>
                                    </form>
                                    <form method="post" action="{{ route('taskstatuses.destroy', ['id' => $taskstatus->id]) }}">
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
                        {{ $taskStatuses->links() }}
                    </nav>
                </div>
            @else
               <p>
                   No tasks statuses found. You need to create at least one task status to be able to create tasks.
               </p>
            @endif
        </div>

        <p class="my-1">
            <a href="{{ route('taskstatuses.create') }}" class="btn btn-sm btn-primary">Add task status</a>
        </p>
    </div>
@endsection