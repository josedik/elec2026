@extends('adminlte::page')

@section('title', 'Candidates')

@section('content_header')
    <div class="d-flex justify-content-between alert alert-info">
        <div>
        <h1>List of political parties registered for: {{ $district->name }}</h1>
        <small>Clicking the icon below Actions allows you to view or register candidates from the chosen party.</small>

        </div>
        <div>
            <a href="{{ route('admin.candidates.index') }}" class="btn btn-sm btn-secondary" title="Return to Previous Page">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>

    </div>

@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @php
                $heads = ['Party', 'Logo', ['label' => 'Actions', 'no-export' => true, 'width' => 14]];

                $btnCreate = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Create list" type="submit">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
            </button>';
                $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="View list of candidates">
                <i class="fa fa-lg fa-fw fa-eye"></i>
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
            <x-adminlte-datatable id="table" :heads="$heads" head-theme="dark" theme="" :config="$config" striped
                hoverable with-buttons>
                @if ($parties->count() > 0)


                    @foreach ($parties as $party)
                        <tr>
                            <td>{{ $party->name }}</td>
                            <td><img src="{{ asset('storage/' . $party->logo_path) }}" alt="logo_path"
                                    class="h-6 flex-shrink-0" width="24px"></td>
                            <td>
                                <form method="GET" action="{{ route('admin.candidates.edit', $party) }}"
                                    style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="party_id" value="{{ $party->id }}">
                                    {!! $btnEdit !!}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2" class="text-center text-danger">No matches are currently registered. Please
                            register matches beforehand.</td>
                    </tr>
                @endif
            </x-adminlte-datatable>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script src="{{ asset('js/main.js') }}" defer></script>
    @if (session('alert'))
        <script>
            Swal.fire(@json(session('alert')))
        </script>
    @endif
@stop
