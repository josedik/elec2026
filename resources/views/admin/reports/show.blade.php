@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Overall voting results</h3>
        </div>
        <div>
            <button class="btn btn-secondary ml-4" onclick="window.history.back()"><i class="fas fa-arrow-left"></i>
                Back</button>

        </div>
    </div>
@stop

@section('content')
    @if ($totalVotes == 0)
        <div class="d-flex justify-content-between  alert alert-warning" role="alert">
            No hay votos registrados para: <h4> {{ $district->name }}.</h4><strong>&#160;</strong>
            <h5>&#160;</h5>
        </div>
    @else
        <div class="d-flex justify-content-between alert alert-info" role="alert">
            Total number of votes registered: <h4> {{ $district->name }}: {{ $totalVotes }} </h4><strong>&#160;</strong>
            <h5>&#160;</h5>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @php
                $order = 1;
                $heads = [
                    ['label' => 'Order', 'width' => 6],
                    ['label' => 'District: ' . $district->name . '/   ' . now(), 'width' => 60],
                    ['label' => 'Votes', 'width' => 10],
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
                bordered compressed with-buttons>
                @foreach ($results as $total)
                    @if ($total->allocated_seats > 0)
                        <tr style="background-color: #d4edda;">
                        @else
                        <tr>
                    @endif
                    <td class="text-right">{{ $order++ }}</td>
                    <td>{{ $total->party->name ?? '' }}</td>
                    <td class="text-right">{{ $total->total ?? 0 }}</td>
                    <td class="text-right">{{ $total->allocated_seats }}</td>
                    <td class="text-right">{{ number_format(($total->total * 100) / $totalVotes, 3, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #aad2ec;">
                    <td> Totales</td>
                    <td> </td>
                    <td class="text-right">{{ $totalVotes * -1 ?? 0 }}</td>
                    <td class="text-right">{{ $district->escanios }}</td>
                    <td class="text-right">100,000</td>
                </tr>

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
@endsection
