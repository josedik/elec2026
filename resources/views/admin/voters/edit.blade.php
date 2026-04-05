@extends('adminlte::page')

@section('title', 'Edit Voter')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Edit Voter</h3>
        </div>
    </div>
@stop


@section('content')
    <form action="{{ route('admin.voters.update', $voter) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" id="dni" name="dni" class="form-control"
                                value="{{ old('dni', $voter->dni) }}" required>
                            @error('dni')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', $voter->name) }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" id="surname" name="surname" class="form-control"
                                value="{{ old('surname', $voter->surname) }}" required>
                            @error('surname')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                                value="{{ old('date_of_birth', $voter->date_of_birth) }}">
                            @error('date_of_birth')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="mesa_id" class="form-label">Mesa</label>
                            <select id="mesa_id" name="mesa_id" class="form-control">
                                <option value="">-- Select Mesa --</option>
                                @foreach ($mesas as $mesa)
                                    <option value="{{ $mesa->id }}"
                                        {{ (string) old('mesa_id', (string) $voter->mesa_id) === (string) $mesa->id ? 'selected' : '' }}>
                                        {{ $mesa->code ?? 'Mesa ' . $mesa->code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mesa_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input type="hidden" name="active" value="0">
                                    <input class="form-check-input" type="checkbox" id="active" name="active"
                                        value="1" {{ old('active', $voter->active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                                @error('active')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input id="photo" name="photo" type="file"
                                class="form-control @error('photo') is-invalid @enderror" value="{{ old('photo', $voter->potho_path ?? '') }}"
                                accept="image" required>
                            @error('photo')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Update Voter</button>
                </div>
            </div>
        </div>
    </form>
@stop
