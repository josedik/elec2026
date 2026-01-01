@extends('adminlte::page')

@section('title', 'Departments')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Departments</h3>
            <small>The code must have 2 digits, clicking on the name will display the provinces.</small>
        </div>
        <div class="pt-5">
            @can('admin.departments.create', App\Models\Department::class)
                <a href="{{ route('admin.departments.create') }}" class="btn btn-sm btn-primary" title="New Department"><i
                        class="fa fa-plus mr-2"></i>New</a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="content-fluid">
        <div class="card">
            <div class="card-body">
                {{-- Setup data for datatables --}}
                @php
                    $heads = [
                        ['label' => 'Code', 'width' => 10],
                        ['label' => 'Name','width' => 76],
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
                <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered with-buttons>
                    @foreach ($departments as $department)
                        <tr>
                            <td>{{ $department->code }}</td>
                            @can('admin.provinces.index', App\Models\Province::class)
                            <td><a href="{{ route('admin.provinces.index', ['department_id' => $department->id]) }}">{{ $department->name }}</a>
                            </td>
                            @endcan
                            <td>
                                @can('admin.departments.edit', $department)
                                <a href="{{ route('admin.departments.edit', $department) }}">
                                    {!! $btnEdit !!}</a>
                                    @endcan
                                @can('admin.departments.destroy', $department)
                                <form method="POST" action="{{ route('admin.departments.destroy', $department) }}"
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

                {{-- Compressed with style options / fill data using the plugin config --}}

            </div>
        </div>
    </div>
@stop


@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
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
