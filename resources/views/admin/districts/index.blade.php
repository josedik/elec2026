@extends('adminlte::page')

@section('title', 'Districts')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Districts of {{ $province->name ?? '' }}</h3>
            <small>The code has 6 characters, the first two are for the department, the next two for the province.</small>
        </div>

        <div>
            <a href="{{ route('admin.provinces.index') }}" class="btn btn-sm btn-secondary" title="Return to Previous Page">
                <i class="fa fa-arrow-left"></i>
            </a>
            @can('admin.districts.create')
                <a href="{{ route('admin.districts.create', ['province_id' => $province->id]) }}" class="btn btn-sm btn-primary"
                    title="New district"><i class="fa fa-plus mr-2"></i>New</a>
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
                    ['label' => 'Code', 'width' => 10],
                    ['label' => 'Name', 'width' => 50],
                    ['label' => 'Population', 'width' => 16],
                    ['label' => 'Area', 'width' => 10],
                    ['label' => 'Actions', 'no-export' => true,'width' => 14],
                ];

                $btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"> <i class="fa fa-lg fa-fw fa-pen"></i></button>';
                $btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"><i class="fa fa-lg fa-fw fa-trash"></i></button>';
                $btnDetails =
                    '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details"><i class="fa fa-lg fa-fw fa-eye"></i></button>';

                $config = [];
            @endphp

            {{-- Minimal example / fill data using the component slot --}}
            <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
                bordered compressed>
                @foreach ($districts as $district)
                    <tr>
                        <td>{{ $district->code }}</td>
                        <td>{{ $district->name }}</td>
                        <td>{{ $district->population }}</td>
                        <td>{{ $district->area }}</td>
                        <td>
                            @can('admin.districts.edit')
                                <a href="{{ route('admin.districts.edit', $district) }}">
                                    {!! $btnEdit !!}</a>
                            @endcan
                            @can('admin.districts.destroy')
                                <form method="POST" action="{{ route('admin.districts.destroy', $district) }}"
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
