@extends('adminlte::page')

@section('title', 'Edit Role')

@section('content_header')
    <h1>User permission list</h1>
    <small>Below you can modify or assign permissions for a user</small>
@stop
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Role: {{ $user->name }}</h3>
        </div>
        <div class="card-body">
            <h3>List of permissions</h3>
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    @foreach ($roles as $role)
                        <div class="form-group col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]"
                                    value="{{ $role->id }}" id="perm-{{ $role->id }}"
                                    {{ $user->permissions->contains($role->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('permissions')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary mt-3">Assign roles</button>
            </form>
            

        </div>
    </div>
@stop
