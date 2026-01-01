@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>User Management</h1>
    <small>Manage user roles and permissions here.
</small>
@stop

@section('content')
    <div class="card">
        
        {{-- Setup data for datatables --}}
        @php
            $heads = ['ID', 'Name','Email', ['label' => 'Actions', 'no-export' => true, 'width' => 14]];

            $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </button>';
            $btnDelete = '<button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                  <i class="fa fa-lg fa-fw fa-trash"></i>
              </button>';

            $config = [];
        @endphp

        {{-- Minimal example / fill data using the component slot --}}
        <x-adminlte-datatable id="table7" :heads="$heads" head-theme="light" theme="" :config="$config" striped
            hoverable>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>                    
                    <td><a href="{{ route('admin.users.edit', $user) }}">
                            {!! $btnEdit !!}</a>

                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;"
                            class="formEliminar">
                            @csrf
                            @method('DELETE')
                            {!! $btnDelete !!}
                        </form>
                    </td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>


@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
