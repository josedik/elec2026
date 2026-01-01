@extends('adminlte::page')

@section('title', 'Edit Political party')

@section('content_header')
    <h1>Edit Political party</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <form action="{{ route('admin.parties.update', $party) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-4">
                        <label for="code">Code</label>
                        <input type="text" name="code" id="code"
                            class="form-control @error('code') is-invalid @enderror" value="{{ old('code') ?? $party->code }}"
                            maxlength="4" >
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-sm-12 col-md-8">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $party->name }}"
                            maxlength="191" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-4">
                        <label for="acronym">Acronym</label>
                        <input type="text" id="acronym" name="acronym" value="{{ old('acronym') ?? $party->acronym }}" title="Acronym"
                            class="form-control @error('acronym') border-red-500 @enderror">
                        @error('acronym')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 col-md-8">
                        <label for="voter_id">President</label>
                        <select id="voter_id" name="voter_id"
                            class="form-control @error('voter_id') border-red-500 @enderror">
                            <option value="">{{ __('Select a voter') }}</option>
                            @foreach ($voters as $voter)
                                <option value="{{ $voter->id }}"
                                    {{ old('voter_id', $party->voter_id) == $voter->id ? 'selected' : '' }}>
                                    {{ $voter->name }} {{ $voter->surname }}
                                </option>
                            @endforeach
                        </select>
                        @error('voter_id')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 col-md-4">
                        <label for="active">Active</label>
                        <select id="active" name="active" class="form-control @error('active') border-red-500 @enderror">
                            <option value="">{{ __('Select status') }}</option>
                            <option value="1" {{ old('active', $party->active) == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ old('active', $party->active) == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                        @error('active')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-12 col-md-4">
                        <label for="logo">Logo</label>
                        <input type="text" id="logo" name="logo" value="{{ old('logo', $party->logo_path) }}" readonly
                            class="form-control @error('logo') border-red-500 @enderror">
                        @error('logo')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex">
                    <input type="file" id="logo_path" name="logo_path"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('logo_path') border-red-500 @enderror"
                        accept="image/*">
                    @error('logo_path')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                    @if ($party->logo_path)
                        <div class="mt-2 flex-items-center">
                            <img id="logito" src="{{ asset('storage/images/' . $party->logo_path) }}"
                                alt="logo_path" class="h-16 flex-shrink-0">
                        </div>
                    @endif
                </div>
                <div class="flex items-center justify-end m-2">
                    <a href="{{ route('admin.parties.index') }}" class="btn btn-secondary">Cancel</a>
                    @can('admin.parties.edit')
                        <button type="submit" class="btn btn-primary ml-4">
                            <i class="fa fa-save mr-1"></i> Update
                        </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        document.getElementById('logo_path').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logito').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@stop
