@extends('adminlte::page')

@section('title', 'Voters')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Voters</h3>
        </div>

        <div>
            @can('admin.voters.create')
                <a href="{{ route('admin.voters.create') }}" class="btn btn-sm btn-primary" title="New voter"><i
                        class="fa fa-plus"></i></a>
            @endcan
        </div>
    </div>
@stop


@section('content')
    <div class="card">
        @php
            $heads = [
                'Name',
                'DNI',
                'Date of birth',
                'Mesa',
                'Active',
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
        <x-adminlte-datatable id="table" :heads="$heads" head-theme="light" theme="" :config="$config" striped
            hoverable with-buttons>
            @if ($voters != null)
                @foreach ($voters as $voter)
                    <tr>
                        <td>{{ $voter->name . ', ' . $voter->surname }}</td>
                        <td>{{ $voter->dni }}</td>
                        <td>{{ $voter->date_of_birth }}</td>
                        <td> {{ $voter->mesa->code ?? '' }} </td>
                        <td>
                            @if ($voter->active)
                                <span class="bg-success text-white px-2 py-1 rounded"><i class="fa fa-check"
                                        aria-hidden="true"></i>
                                </span>
                            @else
                                <span class="bg-danger text-white px-2 py-1 rounded"><i class="fa fa-window-close" aria-hidden="true"></i>
                                </span>
                            @endif
                        </td>

                        <td>
                            @can('admin.voters.edit')
                                <a href="{{ route('admin.voters.edit', $voter) }}">
                                    {!! $btnEdit !!}</a>
                            @endcan
                            @can('admin.voters.destroy')
                                <form method="POST" action="{{ route('admin.voters.destroy', $voter) }}"
                                    style="display: inline;" class="formEliminar">
                                    @csrf
                                    @method('DELETE')
                                    {!! $btnDelete !!}
                                </form>
                            @endcan

                        </td>
                    </tr>
                @endforeach
            @else
                <div class="alert alert-info" role="alert">
                    No voters found.
                </div>
            @endif
        </x-adminlte-datatable>
    </div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('js')

    <script src="{{ asset('js/main.js') }}" defer></script>

@endsection
