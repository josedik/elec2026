@extends('adminlte::page')

@section('title', 'New District')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>New District of <strong class="text-info">{{ $district->province->name }}</strong> </h3>
            <small>The code consists of 2 digits, in addition to the department code (2) and province code (2), and is
                auto-enumerated if left empty</small>
        </div>
        <div>

        </div>
    </div>
@stop

@section('content')
    <div class="content-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.districts.store', $district->id) }}">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ session('department_id') }}">
                    <input type="hidden" name="province_id" value="{{ $district->province_id }}">
                    <div class="row">
                        <!-- Left column -->
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input id="code" name="code" type="text" maxlength="6"
                                    class="form-control @error('code') is-invalid @enderror"
                                    value="{{ old('code', $district->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $district->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="population">Population</label>
                                <input id="population" name="population" type="text" maxlength="4"
                                    class="form-control @error('population') is-invalid @enderror"
                                    value="{{ old('population', $district->population) }}">
                                @error('population')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="area">Area</label>
                                <input id="area" name="area" type="text" maxlength="4"
                                    class="form-control @error('area') is-invalid @enderror"
                                    value="{{ old('area', $district->area) }}">
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <a class="btn btn-secondary btn-sm" href="#"
                                onclick="window.history.back(); return false;">
                                Cancel
                            </a>
                        </div>

                        <div>
                            @can('admin.districts.create')
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save mr-2"></i>Save
                                </button>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop
