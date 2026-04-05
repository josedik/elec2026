@extends('adminlte::page')

@section('title', 'Polling stations')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h4>List of Polling stations</h4>
            <small>On this screen you can also register votes by voting station</small>
        </div>
        <div class="flex">
            <form id="searchForm" action="{{ route('admin.mesas.index') }}" method="GET" class="d-inline-block">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search polling station..." id="search" name="search" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-secondary mr-4" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            @can('admin.mesas.create')
                <a href="{{ route('admin.mesas.create') }}" class="btn btn-sm btn-primary" title="New polling station"><i
                        class="fa fa-plus mr-2"></i>New</a>
            @endcan
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="mesas" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Electors</th>
                        <th>City</th>
                        <th>DNII</th>
                        <th>DNIF</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mesas as $mesa)
                        <tr>
                            <td>{{ $mesa->code }}</td>
                            <td>{{ $mesa->electors }}</td>
                            <td>{{ $mesa->district->name }}</td>
                            <td>{{ $mesa->dnii }}</td>
                            <td>{{ $mesa->dnif }}</td>
                            <td>
                                @can('admin.mesas.edit')
                                    <a href="{{ route('admin.mesas.edit', $mesa) }}" class="btn btn-sm btn-primary"
                                        title="Edit"><i class="fa fa-edit"></i></a>
                                @endcan
                                @can('admin.mesas.show')
                                    <a href="{{ route('admin.mesas.show', $mesa) }}" class="btn btn-sm btn-info"
                                        title="Register votes"><i class="fa fa-cubes"></i></a>
                                @endcan
                                @can('admin.mesas.destroy')
                                    <form method="POST" action="{{ route('admin.mesas.destroy', $mesa) }}"
                                        style="display: inline;" class="formEliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i
                                                class="fa fa-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $mesas->links() }}
            </div>
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
    <script src="https://cdn.tailwindcss.com"></script>
    @if (session('alert'))
        <script>
            Swal.fire(@json(session('alert')))
        </script>
    @endif
@endsection
