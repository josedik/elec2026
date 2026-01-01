@extends('adminlte::page')

@section('title', 'New Polling station')

@section('content_header')
    <h1>Create Polling station</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.mesas.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="code">Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                        value="{{ old('code') }}" required>
                    @error('code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="electors">Electors</label>
                    <input type="number" class="form-control @error('electors') is-invalid @enderror" id="electors"
                        name="electors" value="{{ old('electors') }}" required>
                    @error('electors')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dnii">DNII</label>
                    <input type="number" class="form-control @error('dnii') is-invalid @enderror" id="dnii"
                        name="dnii" value="{{ old('dnii') }}">
                    @error('dnii')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dnif">DNIF</label>
                    <input type="number" class="form-control @error('dnif') is-invalid @enderror" id="dnif"
                        name="dnif" value="{{ old('dnif') }}">
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
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name . '-' . $school->district->name }}</option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                @can('admin.mesas.create')
                    <button type="submit" class="btn btn-primary">Create</button>
                @endcan
                <button class="btn btn-secondary" onclick="window.history.back()">Back</button>
            </form>
    </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
