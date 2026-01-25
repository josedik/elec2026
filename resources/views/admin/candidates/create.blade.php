@extends('adminlte::page')

@section('title', 'Create candidate')

@section('content_header')
    <h1>Create candidates</h1>
    <div class="flex justify-content-between">
        <div>District: {{ $district->name }}</div>
        <div>Political party: {{ $party->name }}</div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.candidates.store') }}" method="POST">
                @csrf
                <input type="hidden" id="district_id" name="district_id" value="{{ $district->id }}">
                <input type="hidden" id="party_id" name="party_id" value="{{ $party->id }}">
                <table>
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filasEnBlanco as $index => $fila)
                            <tr>
                                <td>
                                    {{-- Usamos el índice para nombres de inputs dinámicos --}}
                                    <input type="text" name="registros[{{ $index }}][dni]"
                                        value="{{ old('registros.' . $index . '.dni', $fila['dni']) }}">
                                </td>
                                <td>
                                    <input type="text" name="registros[{{ $index }}][name]"
                                        value="{{ old('registros.' . $index . '.name', $fila['name']) }}">
                                </td>
                                <td>
                                    <input type="text" name="registros[{{ $index }}][surname]"
                                        value="{{ old('registros.' . $index . '.surname', $fila['surname']) }}">
                                </td>
                                <td>
                                    <input type="number" name="registros[{{ $index }}][order]"
                                        value="{{ old('registros.' . $index . '.order', $fila['order']) }}" readonly>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit">Guardar Registros</button>
            </form>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script>
         function searchName() {
            let dni = document.getElementById('dni').value;
            // Solo search si el DNI no está vacío
            if (dni.length > 0) {
                // Usa Fetch API para la petición AJAX
                fetch('{{ route('admin.search.name') }}', { // Ruta definida en web.php
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token de seguridad de Laravel
                        },
                        body: JSON.stringify({
                            dni: dni
                        })
                    })
                    .then(response => response.json()) // Convierte la respuesta a JSON
                    .then(data => {
                        if (data.name) {
                            document.getElementById('name').value = data.name;
                        } else {
                            document.getElementById('name').value = 'No encontrado';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('name').value = 'Search error';
                    });
            } else {
                document.getElementById('name').value = ''; // Limpiar si el DNI se borra
            }
        } 
    </script>

    
@stop
