
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-lg-start">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Update task</h5>
                        <hr>
                        <form method="POST" action="{{ route('task.edit') }}" aria-label="{{ __('Update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>

                                <div class="col-md-6">
                                    <textarea id="description" class="form-control" name="description">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status_id" class="col-md-4 col-form-label text-md-right">Task Status</label>

                                <div class="col-md-6">
                                    <select class="custom-select form-control{{ $errors->has('status_id') ? ' is-invalid' : '' }}" name="status_id" required>
                                        @if ($taskstatuses->isEmpty())
                                            <option disabled>Not found task statuses. You need to create at least one.</option>
                                        @endif
                                        @foreach ($taskstatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('status_id'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('status_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="assignedTo_id" class="col-md-4 col-form-label text-md-right">Assigned To</label>

                                <div class="col-md-6">
                                    <select class="custom-select form-control{{ $errors->has('assignedTo_id') ? ' is-invalid' : '' }}" name="assignedTo_id">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"{{ $user->id === (Auth::id() ?? '' ) ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('assignedTo_id'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('assignedTo_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>

                                <div class="col-md-6">
                                    <input id="tags" type="text" class="form-control{{ $errors->has('tags') ? ' is-invalid' : '' }}"
                                           placeholder="use the delimiter ',' to enter multiple tags"
                                           name="tags" value="{{ old('tags') }}">

                                    @if ($errors->has('tags'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('tags') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Create new task') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection