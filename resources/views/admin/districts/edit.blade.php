@extends('adminlte::page')

@section('title', 'Edit District')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Edit district</h3>
            <small>The first two characters of the code belong to the department, the next two to the province and the next
                two of district</small>
        </div>
        <div>

        </div>
    </div>
@stop

@section('content')
    <div class="content-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.districts.update', $district->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="province_id" value="{{ $province->id }}">
                    <div class="row">
                        <!-- Left column -->
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input id="code" name="code" type="text"
                                    class="form-control @error('code') is-invalid @enderror"
                                    value="{{ old('code', $district->code) }}" maxlength="6">
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
                                <input id="population" name="population" type="number"
                                    class="form-control @error('population') is-invalid @enderror"
                                    value="{{ old('population', $district->population) }}">
                                @error('population')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="area">Area</label>
                                <input id="area" name="area" type="decimal"
                                    class="form-control @error('area') is-invalid @enderror"
                                    value="{{ old('area', $district->area) }}">
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Right column -->
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <a class="btn btn-secondary btn-sm" href="#"
                                onclick="window.history.back(); return false;">
                                Cancel
                            </a>
                        </div>

                        <div>
                            @can('admin.districts.edit')
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save mr-2"></i>Update
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
