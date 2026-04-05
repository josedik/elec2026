@extends('adminlte::page')

@section('title', 'Edit School')

@section('content_header')
    <h1>Edit School</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.schools.update', $school) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="code">Code</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                        value="{{ old('code', $school->code) }}">
                    @error('code')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $school->name) }}">
                    @error('name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address"
                        class="form-control @error('address') is-invalid @enderror"
                        value="{{ old('address', $school->address) }}">
                    @error('address')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="district_id">District</label>
                    <select name="district_id" id="district_id"
                        class="form-control @error('district_id') is-invalid @enderror">
                        <option value="">-- Select District --</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}"
                                {{ (string) old('district_id', $school->district_id) === (string) $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('district_id')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.schools.index') }}" class="btn btn-secondary">Cancel</a>
                    @can('admin.schools.update')
                        <button type="submit" class="btn btn-primary">Update School</button>
                    @endcan

                </div>
            </form>
        </div>
    </div>
@stop
