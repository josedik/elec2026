@extends('adminlte::page')

@section('title')

@section('content_header')
    <div class="d-flex justify-content-between">
        @php
            $msg = match ($cargo) {
                'president' => 'President',
                'senatornac' => 'National Senator',
                'senatorreg' => 'Regional Senator',
                'diputies' => 'Diputies',
                'andino' => 'Andean Parliamentarian',
                default => 'Print Survey',
            };
        @endphp
        <h1>Final Report for {{ $msg }}</h1>
        <div class="div">
            <a href="{{ route('admin.votos.verVotos') }}" class="btn btn-secondary" data-dismiss="modal" title="Back"><i
                    class="fa fa-arrow-left"></i></a>

            {{-- Example button to open modal --}}
            @can('admin.votos.create')
            <x-adminlte-button data-toggle="modal" data-target="#modalCustom" class="bg-teal" icon="fas fa-file-pdf"
                title="View PDF" />
            @endcan
        </div>


    </div>
@stop

@section('content')
    <div class="row row-cols-1">
        <div>
            <div class="modal-dialog modal-xl" style="max-width: 80%; width: 80%;" role="document">
                <div class="modal-content">
                    <form>
                        @csrf
                        <div class="modal-body">
                            <!-- Formulario de AdminLTE -->
                            @php
                                $campo = 'total_' . $cargo;
                                $n = 1;
                                $blancos = 0;
                                $nulos = 0;
                                $emitidos = 0;
                                $validos = 0;
                                foreach ($votos as $item) {
                                    $emitidos += $item->$campo;
                                    if ($item->name == 'BLANK') {
                                        $blancos = $item->$campo;
                                        continue;
                                    } elseif ($item->name == 'INVALID') {
                                        $nulos = $item->$campo;
                                        continue;
                                    }
                                    $validos += $item->$campo;
                                }
                                if ($emitidos == 0) {
                                    $emitidos = ''; // Para evitar división por cero en el cálculo de porcentajes
                                }
                                if ($validos == 0) {
                                    $validos = ''; // Para evitar división por cero en el cálculo de porcentajes
                                }
                            @endphp
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <div class="div">Emitidos: {{ $emitidos }} </div>
                                    <div class="div"> Válidos: {{ $validos }}</div>
                                </div>
                                <table class="table table-primary">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Party</th>
                                            <th scope="col">Votos</th>
                                            <th scope="col">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($votos as $item)
                                            @if ($n == 1)
                                                <tr class="alert alert-success" role="alert">
                                                @elseif ($n == 2)
                                                <tr class="alert alert-info" role="alert">
                                                @elseif ($n >= 3)
                                                <tr class="" role="alert">
                                            @endif
                                            @if ($item->name == 'BLANK' || $item->name == 'INVALID')
                                                @continue
                                            @endif
                                            <td>{{ $n++ }}</td>

                                            <td>{{ $item->name }}</td>
                                            @php
                                                $voto = ($item->$campo > 0 || $emitidos > 0) ? $item->$campo : '';
                                                if ($voto != '') {
                                                    $percent = number_format(($item->$campo / $validos) * 100, 2);
                                                } else {
                                                    $percent = '';
                                                }
                                            @endphp
                                            <td>{{ $voto }}</td>

                                            @if ($percent < 5 && $emitidos > 1)
                                                <td class="end alert alert-danger">
                                                    {{ $percent }}</td>
                                            @else
                                                <td class="end ">{{ $percent }}</td>
                                            @endif

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfooter>
                                        <tr>
                                            <td scope="col">{{ $n++ }}</td>
                                            <td scope="col">INVALID</td>
                                            @php
                                                $votos = $nulos > 0 ? $nulos : '';
                                            @endphp
                                            <td scope="col">{{ $votos }}</td>
                                            @php
                                                $percent =
                                                    $nulos > 0 ? number_format(($nulos / $emitidos) * 100, 2) : '';
                                            @endphp
                                            <td class="end">{{ $percent }}</td>
                                        </tr>
                                        <tr>
                                            <td scope="col">{{ $n++ }}</td>
                                            <td scope="col">BLANK</td>
                                            @php
                                                $votos = $blancos > 0 ? $blancos : '';
                                            @endphp
                                            <td scope="col">{{ $votos }}</td>
                                            @php
                                                $percent =
                                                    $blancos > 0 ? number_format(($blancos / $emitidos) * 100, 2) : '';
                                            @endphp
                                            <td class="end">{{ $percent }}</td>
                                        </tr>
                                    </tfooter>

                                </table>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('admin.votos.verVotos') }}" class="btn btn-secondary"
                                    data-dismiss="modal">Close</a>
                            </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <x-adminlte-modal id="modalCustom" title="" size="lg" theme="teal" icon="fas fa-bell" v-centered
        static-backdrop>
        <div style="height:800px;"> <iframe src="{{ asset('storage//' . $cargo . '.pdf') }}"
                style="width:100%; height:80vh;" frameborder="0"></iframe>
        </div>
    </x-adminlte-modal>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
