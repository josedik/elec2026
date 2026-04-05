@extends('adminlte::page')

@section('title', 'Districts')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Districts</h3>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- Setup data for datatables --}}
            @php
                $heads = [
                    ['label' => 'Code', 'width' => 8],
                    ['label' => 'Name', 'width' => 35],
                    ['label' => 'Province', 'width' => 30],
                    ['label' => 'Population', 'width' =>6],
                    ['label' => 'Area', 'width' => 6],
                    ['label' => 'Actions','no-export', 'width' => 14],
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
            <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered compressed with-buttons>
                @foreach ($districts as $district)
                    <tr>
                        <td>{{ $district->code }}</td>
                        <td>{{ $district->name }}</td>
                        <td>{{ $district->province->name }}</td>
                        <td>{{ $district->population }}</td>
                        <td>{{ $district->area }}</td>

                        <td>
                            <a href="{{ route('admin.districts.assignParties', $district->id) }}" class="btn btn-xs btn-default text-info mx-1 shadow" title="Assign Parties">
                                <i class="fa fa-lg fa-fw fa-link"></i>
                            </a>
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
@endsection
