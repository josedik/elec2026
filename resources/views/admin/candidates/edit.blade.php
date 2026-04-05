@extends('adminlte::page')

@section('title', 'List of Candidates')

@section('content_header')
    <div class="d-flex justify-content-between alert alert-info">
        @php
            $id = $district->id;
        @endphp
        <div><img src="{{ asset('storage/images/' . $party->logo_path) }}" alt="Party logo" class="h-6 flex-shrink-0 mr-2"
                width="36px">Political party: <strong>{{ $party->name }}</strong></div>
        <div>
            <h3>Candidates</h3>
        </div>
        <div>District: <strong>{{ $district->name }}</strong></div>
        <div>Governors: <strong>{{ $district->escanios }}</strong></div>
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.candidates.show', ['candidate' => $id]) }}" class="btn btn-primary btn-sm mr-2"
                title="Return previous">
                <i class="fa fa-arrow-left mt-2"></i>
            </a>
            <button onclick="generarPDF()" class="btn btn-danger btn-sm " title="Print list PDF"><i
                    class="fas fa-print"></i></button>
        </div>
    </div>

    <div class="d-flex justify-content-start ">
        <small>If the data is not complete, you can modify it by clicking on the right button, in the actions column.
            Order 0 corresponds to the Mayor, the others are the councilors of the municipality in order of registration in
            the JNE.</small>

        <!-- Checkbox Toggle -->

    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="candidates-table" class="table table-striped table-bordered shadow-lg mt-2" style="width:100%">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Order</th>
                        <th>DNI</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th class="text text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidates as $candidate)
                        <tr @if ($candidate['order'] == 0) class="bg-warning" @endif>
                            <td>{{ $candidate['order'] }}</td>
                            <td>{{ $candidate['dni'] }}</td>
                            <td>{{ $candidate['name'] }}</td>
                            <td>{{ $candidate['surname'] }} </td>
                            <td class="text text-center">
                                @can('admin.candidates.create')
                                    
                                
                                <a href="{{ route('admin.candidates.obtenerRegistro', [
                                    'id' => $candidate['id'],
                                    'party_id' => $party['id'],
                                ]) }}"
                                    class="btn btn-info btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Botón para abrir el modal -->
                                <button class="btn btn-primary btn-sm" title="Show photo" data-toggle="modal"
                                    data-target="#imageModal"
                                    data-ruta="{{ asset('storage/photos/' . $candidate['dni']) }}"
                                    data-nombre="{{ $candidate['name'] }}">
                                    <i class="fa fa-camera"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal para presentar el PDF -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <iframe id="pdfViewer" style="width: 100%; height: 600px;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para foto-->

    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImageName"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImageSource" class="img-fluid" alt="Photo">
                </div>
            </div>

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
    @if (session('alert'))
        <script>
            Swal.fire(@json(session('alert')))
        </script>
    @endif

    <script>
        function generarPDF() {
            fetch(
                    '{{ route('admin.candidates.printListPDF') }}?district_id={{ $district->id }}&party_id={{ $party->id }}'
                )
                .then(response => response.blob())
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    document.getElementById('pdfViewer').src = url;
                    $('#pdfModal').modal('show');
                });
        }
    </script>

    <script>
        $('#imageModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Botón que activó el modal
            var ruta = button.data('ruta'); // Extraer info de atributos data-*
            var nombre = button.data('nombre');

            var modal = $(this);
            modal.find('.modal-title').text(nombre);
            modal.find('#modalImageSource').attr('src', ruta);
        });
    </script>
@endsection
