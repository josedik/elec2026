@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1>System Roles</h1>
    <small>Below you can create or modify system roles</small>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <x-adminlte-button label="New" theme="success" icon="fas fa-plus" class="float-right" data-toggle="modal"
                data-target="#modalPurple" />

        </div>
        {{-- Setup data for datatables --}}
        @php
            $heads = ['ID', 'Name', ['label' => 'Actions', 'no-export' => true, 'width' => 14]];

            $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Assign Permissions">
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
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    {{-- <td>{{ $role->name }}</td> --}}
                    <td ondblclick="$('#modalEdit{{ $role->id }}').modal('show')" style="cursor: pointer;">
                        {{ $role->name }}
                    </td>
                    <x-adminlte-modal id="modalEdit{{ $role->id }}" title="Edit Role" theme="info" icon="fas fa-edit" size="lg" disable-animations>
                        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <x-adminlte-input name="name" label="Role Name" value="{{ $role->name }}" fgroup-class="col-md-8" />
                            </div>
                            <x-adminlte-button class="mt-2" type="submit" label="Save Changes" theme="primary" icon="fas fa-save"/>
                        </form>
                    </x-adminlte-modal>
                    <td><a href="{{ route('admin.roles.edit', $role) }}">
                            {!! $btnEdit !!}</a>

                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" style="display: inline;"
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
    {{-- Themed --}}
    <x-adminlte-modal id="modalPurple" title="New Role" theme="primary" icon="fas fa-bolt" size='lg'
        disable-animations>
        <form action="{{ route('admin.roles.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="row">
                <x-adminlte-input name="name" label="Role Name" placeholder="Enter role name" fgroup-class="col-md-6"
                    disable-feedback />
            </div>
            <x-adminlte-button class="mt-2" type="submit" label="Create Role" theme="success" icon="fas fa-lg fa-save" />
        </form>
    </x-adminlte-modal>

@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
