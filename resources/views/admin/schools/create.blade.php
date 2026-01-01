@extends('adminlte::page')

@section('title', 'New School')

@section('content_header')
    <h1>Create School</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schools.store') }}">
                @csrf

                <div class="form-group">
                    <label for="code">Code</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                        value="{{ old('code') }}" maxlength="5">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="district_id">District</label>
                    <select name="district_id" id="district_id"
                        class="form-control @error('district_id') is-invalid @enderror">
                        <option value="">-- Select district --</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                {{ $district->name . ' ' . $district->province->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('district_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.schools.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                    @can('admin.schools.create')
                        <button type="submit" class="btn btn-primary">Create</button>
                    @endcan

                </div>
            </form>
        </div>
    </div>
@stop
