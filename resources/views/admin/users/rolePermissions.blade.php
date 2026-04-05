@extends('adminlte::page')

@section('title', 'Edit Role')

@section('content_header')
    <h1>Role permission list</h1>
    <small>Below you can modify or assign permissions for a role</small>
@stop
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Role: {{ $role->name }}</h3>
        </div>
        <div class="card-body">
            <h3>List of permissions</h3>
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="form-group col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="{{ $permission->id }}" id="perm-{{ $permission->id }}"
                                    {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm-{{ $permission->id }}">
                                    {{ $permission->description }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('permissions')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary mt-3">Assign permissions</button>
            </form>
            

        </div>
    </div>
@stop
