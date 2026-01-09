@extends('adminlte::page')

@section('title', 'Candidates')

@section('content_header')
    <h1>Candidates from {{ $district->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @php
            $heads = [
                'Party',
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
                @foreach ($parties as $party)
                    <tr>
                        <td>{{ $party->name }}</td>

                        <td>
                            @can('admin.candidates.edit')
                                <a href="{{ route('admin.candidates.edit', $voter) }}">
                                    {!! $btnEdit !!}</a>
                            @endcan
                            @can('admin.candidates.destroy')
                                <form method="POST" action="{{ route('admin.candidates.destroy', $voter) }}"
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

@section('css')
    
@stop

@section('js')
    
@stop