@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="display-6">Users</h1>
        <div class="d-inline-block">
            @if (! $users->isEmpty())
                <table class="table table-bordered table-white table-sm">
                    <thead class="thead-dark">
                    <tr class="text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Updated_at</th>
                        <th scope="col">Created_at</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->updated_at }}</td>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div>
                    <nav aria-label="Pages">
                        {{ $users->links() }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection