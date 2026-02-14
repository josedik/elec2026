@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Overall voting results by district</h3>
        </div>
        <div class="d-flex justify-content-between">

            <button class="btn btn-secondary btn-sm ml-4" title="Back" onclick="window.history.back()"><i
                    class="fa fa-lg fa-fw fa-arrow-left"></i></button>
            @if ($totalVotes > 0)
                <a href="{{ route('admin.reports.edit', $district->id) }}" class="btn btn-success btn-sm  mx-1" title="View Governors">
                    <i class="fa fa-lg fa-fw fa-thumbs-up mt-2" aria-hidden="true"></i>
                </a>
                <div>
                <x-adminlte-button theme="danger" icon="fa fa-lg fa-fw fa-file" data-toggle="modal" title="Print pdf"
                    data-target="#modalPurple" />
            </div>
            @endif

        </div>
    </div>
@stop

@section('content')
    @if ($totalVotes == 0 || $totalVotes == null)
        <div class="d-flex justify-content-between  alert alert-warning" role="alert">
            There is no recorded data for the district: <h4> {{ $district->name }}.</h4><strong>&#160;</strong>
            <h5>&#160;</h5>
        </div>
    @else
        <div class="d-flex justify-content-between alert alert-info" role="alert">
            <div>
                <h4>District: {{ $district->name }} </h4>
            </div>
            <div>
                <h4>Votes registered: {{ $totalVotes }}</h4>
            </div>
            <div>{{ date('H:i:s')  }}</div>

        </div>
        <div class="card">
            <div class="card-body">
                @php
                    $blancos = 0;
                    $nulos = 0;
                    $order = 1;
                    $votosValidos = $totalVotes;
                    $heads = [
                        ['label' => 'Order', 'width' => 5],
                        ['label' => 'Party', 'width' => 60],
                        ['label' => 'Logo', 'width' => 5],
                        ['label' => 'Votes V', 'width' => 10],
                        ['label' => 'Gobernors', 'width' => 10],
                        ['label' => '(%)', 'width' => 10],
                    ];
                    $config = [
                        'order' => [[2, 'desc']],
                        'pageLength' => 50,
                        'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'All']],
                    ];
                @endphp
                <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
                    bordered compressed>
                    @foreach ($results as $result)
                        @if ($result->party->code == env('BLANK_VOTES_CODE'))
                            @php
                                $blancos += $result->total;
                                $votosValidos = $votosValidos - $blancos;
                            @endphp
                            @continue
                        @endif
                        @if ($result->party->code == env('INVALID_VOTES_CODE'))
                            @php
                                $nulos += $result->total;
                                $votosValidos = $votosValidos - $nulos;
                            @endphp
                            @continue
                        @endif
                    @endforeach
                    @foreach ($results as $total)
                        @if ($total->party->code == env('BLANK_VOTES_CODE'))
                            @continue
                        @endif
                        @if ($total->party->code == env('INVALID_VOTES_CODE'))
                            @continue
                        @endif
                        @if ($total->allocated_seats > 0)
                            <tr style="background-color: #d4edda;">
                            @else
                            <tr>
                        @endif
                        <td class="text-right">{{ $order++ }}</td>
                        <td>{{ $total->party->name ?? '' }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $total->party->logo_path) }}" alt="logo_path"
                                class="h-6 flex-shrink-0" width="36px">

                        </td>
                        <td class="text-right">{{ $total->total ?? 0 }}</td>
                        <td class="text-right">{{ $total->allocated_seats }}</td>
                        <td class="text-right">{{ number_format(($total->total * 100) / $votosValidos, 3, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tfoot>
                        <tr>
                            <td class="text-right">{{ $order++ }}</td>
                            <td>INVALID</td>
                            <td class="text-right">{{ $nulos ?? 0 }}</td>
                            <td></td>
                            <td class="text-right"></td>
                            <td class="text-right">{{ number_format(($nulos * 100) / $totalVotes, 3, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">{{ $order++ }}</td>
                            <td>BLANK</td>
                            <td class="text-right">{{ $blancos ?? 0 }}</td>
                            <td></td>
                            <td class="text-right"></td>
                            <td class="text-right">{{ number_format(($blancos * 100) / $totalVotes, 3, ',', '.') }}</td>
                        </tr>
                        <tr style="background-color: #aad2ec;">
                            <td> Totales</td>
                            <td></td>
                            <td class="text-right">{{ $totalVotes ?? 0 }}</td>
                            <td class="text-right">{{ $votosValidos }} </td>
                            <td class="text-right">{{ $district->escanios }}</td>
                            <td class="text-right">100,000</td>
                        </tr>
                    </tfoot>
                </x-adminlte-datatable>
            </div>
        </div>
        <!-- Modal para presentar el PDF -->
        <div class="modal fade" id="modalPurple" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <iframe id="pdfViewer" src="/storage/lista.pdf" style="width: 100%; height: 600px;"
                            frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
