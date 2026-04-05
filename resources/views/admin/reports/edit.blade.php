@extends('adminlte::page')

@section('title', 'Report')

@section('content_header')
    <div class="d-flex justify-content-between alert alert-info">
        <h1>Elected councilors: {{ $district->name }}</h1>
        @if ($mensaje != ' ')
            <div class="bg-warning">
                {{ $mensaje }}
            </div>
            @endsession
            <div class="d-flex justify-content-between">
                <div>
                    <a class="btn btn-secondary btn-sm" href="#" onclick="window.history.back(); return false;">
                        <i class="fa fa-arrow-left"></i>
                    </a>

                    </a>
                </div>
                <div>
                    <button type="button" class="btn btn-danger btn-sm ml-2" data-toggle="modal" data-target="#modalPDF"
                        title="View PDF"><i class="fas fa-file-pdf"></i>
                    </button>
                </div>
            </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @php
                $ord = 1;
                $heads = [
                    ['label' => '#', 'width' => 10],
                    ['label' => 'Party', 'width' => 40],
                    ['label' => 'Name', 'width' => 40],
                    ['label' => 'Function', 'width' => 10],
                ];

                $btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"> <i class="fa fa-lg fa-fw fa-pen"></i></button>';
                $btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"><i class="fa fa-lg fa-fw fa-trash"></i></button>';
                $btnDetails =
                    '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details"><i class="fa fa-lg fa-fw fa-eye"></i></button>';

                $config = [];
            @endphp
            <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
                bordered compressed>
                @foreach ($lista as $list)
                    <tr>
                        <td>{{ $ord++ }}</td>
                        <td>{{ $list->party->name }}</td>
                        <td>{{ $list->voter->name }} {{ $list->voter->surname }}</td>
                        <td>{{ $list->order == 0 ? 'Mayor' : '' }}</td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
    <div class="modal fade" id="modalPDF" tabindex="-1" role="dialog" aria-labelledby="modalPDFLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document"> <!-- modal-xl para pantallas grandes -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPDFLabel">PDF preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Iframe que muestra el PDF -->
                    <iframe src="/storage/concejo.pdf" frameborder="0" style="width:100%; height:70vh;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@stop
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
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
