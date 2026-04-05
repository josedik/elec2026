@extends('adminlte::page')

@section('title', 'Political parties')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Political parties</h3>
        </div>

        <div>
            @can('admin.parties.create')
                <a href="{{ route('admin.parties.create') }}" class="btn btn-sm btn-primary" title="New Political party"><i
                        class="fa fa-plus"></i></a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Code',
                    'Name',
                    'Acronym',
                    'Principal',
                    'Active',
                    'Affiliates',
                    ['label'=>'Logo', 'width'=>11],
                    ['label' => 'Actions', 'no-export' => true, 'width' => 14],
                ];

                $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
                $btnDelete = '<button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';
                $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="">
                   <i class="fa fa-lg fa-fw fa-eye"></i>
               </button>';

                $config = [];
            @endphp

            {{-- Minimal example / fill data using the component slot --}}
            <x-adminlte-datatable id="table" :heads="$heads" head-theme="light" theme="" :config="$config" striped hoverable with-buttons>
                @foreach ($parties as $party)
                    <tr>
                        <td>{{ $party->code }}</td>
                        <td>{{ $party->name }}</td>
                        <td>{{ $party->acronym }}</td>
                        <td>{{ $party->voter->name ?? ''}}  {{  $party->voter->surname ?? '' }}</td>
                        <td>{{ $party->active ? 'Yes' : 'No' }}</td>
                        <td>{{ $party->voters ?? '' }}</td>

                        <td><img src="{{ asset('storage/images/' . $party->logo_path) }}" alt="logo_path" class="h-6 flex-shrink-0" width="36px"></td>
                        <td>
                            @can('admin.parties.edit')
                                <a href="{{ route('admin.parties.edit', $party) }}">
                                    {!! $btnEdit !!}
                                </a>
                            @endcan
                            @can('admin.parties.destroy')
                                <form method="POST" action="{{ route('admin.parties.destroy', $party) }}"
                                    style="display: inline;" class="formEliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow"
                                        title="Delete">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
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
