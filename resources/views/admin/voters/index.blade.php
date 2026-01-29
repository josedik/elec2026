@extends('adminlte::page')

@section('title', 'Voters')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div>
            <h3>Voters</h3>
            <small></small>
        </div>
        <div>
            @can('admin.voters.create')
                <a href="{{ route('admin.voters.create') }}" class="btn btn-sm btn-primary" title="New voter"><i
                        class="fa fa-plus mr-2"></i>New</a>
            @endcan
        </div>
</div>@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped" id="voters-table">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Active</th>
                        @can('admin.voters.create')
                        <th>Actions</th>
                        @endcan
                    </tr>
                </thead>
            </table>
        </div>
    </div>

@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="{{ asset('js/main.js') }}" defer></script>
    @if (session('alert'))
        <script>
            Swal.fire(@json(session('alert')))
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#voters-table').DataTable({
                processing: true,
                serverSide: true, // Opcional: para gran volumen de datos [10]
                ajax: "{{ route('admin.voters.getVoters') }}",
                columns: [{
                        data: 'dni',
                        name: 'dni'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'surname',
                        name: 'surname'
                    },
                    {
                        data: 'active',
                        name: 'active'
                    },
                    @can('admin.voters.create')
                        {
                            data: 'action',
                            name: 'action'
                        }
                    @endcan

                ]
            });
            // delete voter via AJAX when clicking buttons with id like "btn-<id>"
            $('#voters-table').on('click', '[id^="btn-"]', function(e) {
                e.preventDefault();
                const btn = $(this);
                const id = btn.attr('id').replace('btn-', '');
                Swal.fire({
                    title: 'Delete record?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('admin.voters.destroy', ':id') }}".replace(':id',
                            id),
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#voters-table').DataTable().ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted ',
                                text: response.message || ''
                            });
                        },
                        error: function(xhr) {
                            let msg = 'Error deleting';
                            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr
                                .responseJSON.message;
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: msg
                            });
                        }
                    });
                });
            });
        });
    </script>
@stop
