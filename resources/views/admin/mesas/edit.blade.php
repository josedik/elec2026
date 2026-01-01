@extends('adminlte::page')

@section('title', 'Edit Polling station')

@section('content_header')
    <h1>Edit Polling station</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.mesas.update', $mesa->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="code">Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                        value="{{ old('code', $mesa->code) }}" required>
                    @error('code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="electors">Electors</label>
                    <input type="number" class="form-control @error('electors') is-invalid @enderror" id="electors"
                        name="electors" value="{{ old('electors', $mesa->electors) }}" required>
                    @error('electors')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dnii">DNI Initial</label>
                    <input type="number" class="form-control @error('dnii') is-invalid @enderror" id="dnii"
                        name="dnii" value="{{ old('dnii', $mesa->dnii) }}">
                    @error('dnii')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dnif">DNI Final</label>
                    <input type="number" class="form-control @error('dnif') is-invalid @enderror" id="dnif"
                        name="dnif" value="{{ old('dnif', $mesa->dnif) }}">
                    @error('dnif')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="school_id">School</label>
                    <select class="form-control @error('school_id') is-invalid @enderror" id="school_id" name="school_id"
                        required>
                        <option value="">Select a school</option>
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}"
                                {{ old('school_id', $mesa->school_id) == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                @can('admin.mesas.edit')
                    <button type="submit" class="btn btn-primary">Update</button>
                @endcan
                <button class="btn btn-secondary ml-4" onclick="window.history.back()">Cancel</button>
            </form>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
