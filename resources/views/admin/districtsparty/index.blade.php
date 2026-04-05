@extends('adminlte::page')

@section('title', 'Assign parties by District')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Assign parties by District</h1>
            <small>When assigning parties to the capital district, they are automatically assigned to all districts of the province. Click on the name of district, you will see the parties assigned to the district and you can also establish the number
                of governors for the district.</small>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    ['label' => 'Code', 'width' => 6],
                    ['label' => 'Name', 'width' => 35],
                    ['label' => 'Province', 'width' => 30],
                    ['label' => 'Population', 'width' => 6],
                    ['label' => 'Area', 'width' => 6],
                    ['label' => 'Governors', 'width' => 6],
                    ['label' => 'Actions', 'no-export', 'width' => 14],
                ];

                $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
                $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';
                $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Assign Parties">
                   <i class="fa fa-lg fa-fw fa-link"></i>
               </button>';

                $config = [];
            @endphp

            {{-- Minimal example / fill data using the component slot --}}
            <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
                bordered compressed with-buttons>
                @foreach ($districts as $district)
                    <tr>
                        <td class="text text-right">{{ $district->code }}</td>
                        <td>
                            <a href="{{ route('admin.districtsparty.show', $district->id) }}" title="View Parties">
                                {{ $district->name }}</a>

                        </td>
                        <td>{{ $district->province->name }}</td>
                        <td class="text text-right">{{ $district->population }}</td>
                        <td class="text text-right">{{ $district->area }}</td>
                        <td class="text text-center">{{ $district->escanios }}</td>
                        <td class="text text-center">
                            @can('admin.districtsparty.show')
                            @endcan
                            @can('admin.districtsparty.edit')
                                <a href="{{ route('admin.districtsparty.edit', $district->id) }}"
                                    class="btn btn-xs btn-default text-info mx-1 shadow" title="Assign Parties">
                                    <i class="fa fa-flag" aria-hidden="true"></i>

                                </a>
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
@endsection
