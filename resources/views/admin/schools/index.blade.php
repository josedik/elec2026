@extends('adminlte::page')

@section('title', 'Schools')

@section('content_header')
<div class="d-flex justify-content-between">
        <div>
            <h3>Schools</h3>
            <small></small>
        </div>
        <div>
            @can('admin.schools.create')
            <a href="{{ route('admin.schools.create') }}" class="btn btn-sm btn-primary" title="New school"><i
                    class="fa fa-plus mr-2"></i>New</a>
            @endcan
        </div>
    </div>@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Code', 'Name',                    
                    'Address','District',
                    ['label' => 'Actions', 'no-export' => true, 'width' => 14],
                ];

                $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
                $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';
                $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                   <i class="fa fa-lg fa-fw fa-eye"></i>
               </button>';

                $config = [];
            @endphp

            {{-- Minimal example / fill data using the component slot --}}
            <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
                bordered compressed>
                @foreach ($schools as $school)
                    <tr>
                        <td>{{ $school->code }}</td>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->address }}</td>
                        <td>{{ $school->district->name }}</td>
                        <td>
                            @can('admin.schools.edit')
                            <a href="{{ route('admin.schools.edit', $school) }}">
                                {!! $btnEdit !!}</a>
                            @endcan
                            @can('admin.schools.destroy')

                            <form method="POST" action="{{ route('admin.schools.destroy', $school) }}"
                                style="display: inline;" class="formEliminar">
                                @csrf
                                @method('DELETE')
                                {!! $btnDelete !!}
                            </form>
                            @endcan
                        </td>

                    </tr>
                @endforeach
            </x-adminlte-datatable>

        </div>
    </div>
@stop
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="{{ asset('js/main.js') }}" defer></script>
        @if (session('alert'))
        <script>
            Swal.fire(@json(session('alert')))
        </script>
    @endif
@stop
