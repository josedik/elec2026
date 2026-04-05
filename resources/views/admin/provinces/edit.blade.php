@extends('adminlte::page')

@section('title', 'Edit Province')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Edit Province</h3>
            <small>The first two characters of the code belong to the department, the next two to the province</small>
        </div>
        <div>

        </div>
    </div>
@stop

@section('content')
    <div class="content-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.provinces.update', $province->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="department_id" value="{{ $province->department_id }}">
                    <div class="row">
                        <!-- Left column -->
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $province->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input id="code" name="code" type="text"
                                    class="form-control @error('code') is-invalid @enderror"
                                    value="{{ old('code', $province->code) }}">
                                @error('code')
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
                            @can('admin.provinces.edit')
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
