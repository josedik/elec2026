@extends('adminlte::page')

@section('title', 'Provinces')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Provinces of {{ $department->name }}</h3>
            <small>The code has 4 characters, the first two are from the department. Clicking on the name will display the
                districts.</small>
        </div>

        <div>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-sm btn-secondary" title="Return to Previous Page">
                <i class="fa fa-arrow-left"></i>
            </a>
            @can('admin.provinces.create')
                <a href="{{ route('admin.provinces.create', ['department_id' => $department->id]) }}"
                    class="btn btn-sm btn-primary" title="New province"><i class="fa fa-plus mr-2"></i>New</a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- Setup data for datatables --}}
            @php
                $heads = [
                    ['label' => 'Code',  'width' => 10],
                    ['label' => 'Name',  'width' => 50],
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
                @foreach ($provinces as $province)
                    <tr>
                        <td>{{ $province->code }}</td>
                        <td><a href="{{ route('admin.districts.index', ['province_id' => $province->id]) }}">
                                {{ $province->name }}</a>
                        </td>
                        <td>
                            @can('admin.provinces.edit')
                                <a href="{{ route('admin.provinces.edit', $province) }}">
                                    {!! $btnEdit !!}</a>
                            @endcan
                            @can('admin.provinces.destroy')
                                <form method="POST" action="{{ route('admin.provinces.destroy', $province) }}"
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
