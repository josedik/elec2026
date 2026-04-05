@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <h1>Permission Management</h1>
    <small>Manage user permissions
</small>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <x-adminlte-button label="New" theme="success" icon="fas fa-plus" class="float-right" data-toggle="modal" data-target="#modalPurple"/>

        </div>
        {{-- Setup data for datatables --}}
        @php
            $heads = ['ID', 'Name', ['label' => 'Actions', 'no-export' => true, 'width' => 14]];

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
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->description }}</td>
                    <td><a href="{{ route('admin.permissions.edit', $permission) }}">
                            {!! $btnEdit !!}</a>

                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" style="display: inline;"
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
<x-adminlte-modal id="modalPurple" title="New permission" theme="primary"
    icon="fas fa-bolt" size='lg' disable-animations>
    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf
        <div class="row">
            <x-adminlte-input name="name" label="permission Name" placeholder="Enter permission name" fgroup-class="col-md-6" disable-feedback />
        </div>
        <x-adminlte-button class="mt-2" type="submit" label="Create permission" theme="success" icon="fas fa-lg fa-save"/>
    </form>
</x-adminlte-modal>
{{-- Example button to open modal --}}

@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
