@extends('adminlte::page')

@section('title', 'Create Voter')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Create Voter</h3>
        </div>
    </div>
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.voters.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="photo_path" type="text">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input id="dni" name="dni" type="text"
                                class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni') }}"
                                required>
                            @error('dni')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                required>
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="surname">Surname</label>
                            <input id="surname" name="surname" type="text"
                                class="form-control @error('surname') is-invalid @enderror" value="{{ old('surname') }}"
                                required>
                            @error('surname')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input id="date_of_birth" name="date_of_birth" type="date"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mesa_id">Mesa</label>
                            <select id="mesa_id" name="mesa_id"
                                class="form-control @error('mesa_id') is-invalid @enderror">
                                <option value="">Select mesa</option>
                                @foreach ($mesas ?? [] as $mesa)
                                    <option value="{{ $mesa['id'] }}"
                                        {{ old('mesa_id') == $mesa['id'] ? 'selected' : '' }}>
                                        {{ $mesa['code'] ?? "Mesa #{$mesa['code']}" }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mesa_id')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4 form-group form-check mt-auto">
                        <input type="hidden" name="active" value="0">
                        <input id="active" name="active" type="checkbox" class="form-check-input" value="1"
                            {{ old('active', 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active"><strong>Active</strong></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input id="photo" name="photo" type="file"
                                class="form-control @error('photo') is-invalid @enderror" value="{{ old('photo') }}"
                                accept="image" >
                            @error('photo')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Create Voter</button>
                    <div>
                        <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                    <div></div>
                </div>
            </form>

        </div>
    </div>
@stop
