@extends('adminlte::page')

@section('title', 'New Province')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>New Province of <strong class="text-info">{{ $province->department->name }}</strong> </h3>
            <small>The code consists of 2 digits, in addition to the department code, and is auto-enumerated if left empty</small>
        </div>
        <div>
            
        </div>
    </div>
@stop

@section('content')
    <div class="content-fluid">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.provinces.store', $province->id) }}">
                    <input type="hidden" name="department_id" value="{{ $province->department_id }}">
                    @csrf
                    <div class="card card-default">
                        <div class="card-body">
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
                                        <input id="code" name="code" type="text" maxlength="4"
                                            class="form-control @error('code') is-invalid @enderror"
                                            value="{{ old('code', $province->code) }}">
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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
                                @can('admin.provinces.create')
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save mr-2"></i>Save
                                </button>
                                @endcan
                            </div>
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


