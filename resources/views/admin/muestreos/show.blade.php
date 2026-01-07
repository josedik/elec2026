@extends('adminlte::page')

@section('title', 'List of Parties by District')

@section('content_header')
@if ($partidos==0)
<div class="alert alert-danger" role="alert">
    <h1 class="text text-center">There are no parties for the district: {{ $district->name }}</h1>
    <small>Print only one copy for each collector. A personal value is printed for each one.
    <p>A form will be issued with 10 parties with the name Party_xx.</p></small>
    @php
    $district->parties = collect(range(1, 10))->map(function($i) {
                return (object)[
                    'id'=>$i,
                    'name' => 'party_' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'votes'=>''];
            });
    @endphp
@else
<div class="alert alert-info" role="alert">
    <h1 class="text text-center">Parties of District: {{ $district->name }}</h1>
    <small>Print only one copy for each collector. A personal value is printed for each one.</small>
@endif
</div>    

    
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.muestreos.store') }}" method="POST">
                @csrf
                @php
                    $order = 1;
                    $heads = [
                        '#',
                        'Parties' ,
                        'Votes',
                    ];

                    $btnEdit =
                        '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"> <i class="fa fa-lg fa-fw fa-pen"></i></button>';
                    $btnDelete =
                        '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"><i class="fa fa-lg fa-fw fa-trash"></i></button>';
                    $btnDetails =
                        '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details"><i class="fa fa-lg fa-fw fa-eye"></i></button>';

                    $config = [
                        "pageLength"=> 50,
                    ];
                @endphp

                {{-- Minimal example / fill data using the component slot --}}
                <x-adminlte-datatable id="table" :heads="$heads" head-theme="dark" :config="$config" striped hoverable bordered compressed>
                        @foreach ($district->parties as $party)
                            <tr>
                                <td class="text-right">{{ $order++ }}</td>
                                <td>{{ $party->name }}</td>
                                <td>
                                    <input type="number" name="votes[{{ $party->id }}]" class="form-control"
                                        placeholder="Here register votes for the poll" min="0" readonly>
                                </td>
                            </tr>

                        @endforeach
                </x-adminlte-datatable>
                <input type="hidden" name="district_id" value="{{ $district->id }}">
                <input type="hidden" name="samples" value="{{ $samples }}">
                <div class="mt-4">
                    @if (true)
                    <button type="submit" class="btn btn-primary ml-2">
                        <i class="fa fa-print"></i> Print Survey Sheet
                    </button>

                    @endif
                    <a href="{{ route('admin.muestreos.index') }}" class="btn btn-secondary ml-2">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)


@section('css')

@stop

@section('js')

@stop
