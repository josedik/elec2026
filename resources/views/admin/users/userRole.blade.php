@extends('adminlte::page')

@section('title', 'Roles User')

@section('content_header')
    <h1>Roles User</h1>
    <small>Management roles user </small>
@stop
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Roles user: {{ $user->name }}</h3>
        </div>
        <div class="card-body">
            <h3>List of roles</h3>
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    @foreach ($roles as $role)
                        <div class="form-group col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]"
                                    value="{{ $role->id }}" id="perm-{{ $role->id }}"
                                    {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm-{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('roles')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary mt-3">Assign roles</button>
            </form>
            

        </div>
    </div>
@stop
