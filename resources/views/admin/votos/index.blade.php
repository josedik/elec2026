@extends('adminlte::page')

@section('title')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div class="col-auto">
            <h1>Register votes</h1>
            <small class="text-muted">Enter the votes for each political party in the corresponding fields. </small><br><small class="text-muted">The total number of votes must match the number of votes cast.</small>
        </div>
        <div class="card">
            <div class="card-body">
                <div>
                    <form action="{{ route('admin.votos.index') }}" method="GET" class="form-inline">
                        @csrf
                        <label for="code">Polling station: </label>
                        <input type="number" placeholder="Search polling station by number" name="code" id = "code"
                            class="form-control ml-2" min="0" max="999999" value ="{{ request('code') }}">

                        <button type="submit" class="btn btn-primary btn-sm mr-3"> <i
                                class="fas fa-search fa-fw"></i></button>
                        @if ($mesa)
                            <label for="code">Votes cast: </label>
                            <input id="emitidospre" name="emitidospre" type="number" min:0 max="{{ $mesa->electors }}"  
                                title="Votes cast" onblur="transferirValor()"
                                class="form-control no-enter ml-2 @error('emitidospre') is-invalid @enderror"
                                value="{{ $mesa->emitidospre }}">

                                @php
                                $n=1;
                                @endphp
                        @endif

                    </form>

                </div>
            </div>
        </div>

    </div>
@stop

@section('content')
    <div class="card">
        @if ($mesa)
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <label class="alert alert-info mr-4"> {{ $mesa->code }}</label>
                    <label class="alert alert-success mr-4"> District: {{ $mesa->district->name }}</label>
                    <label class=" alert alert-info mr-4" title="Votes cast/Electors"> Cast/Tot: {{ $mesa->emitidospre }} /
                        {{ $mesa->electors }}</label>
                </div>
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                        <small>
                            If you don't see the data, click the back arrow on your browser to retrieve it.
                        </small>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.votos.store', ['mesa_id' => $mesa->id]) }}" method="POST" id="miFormulario">
                    @csrf
                    <input type="hidden" name="emitidos" id="emitidos" value="{{ $mesa->emitidospre ?? 0 }}">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Political party</th>
                                <th>President</th>
                                <th>Senator</th>
                                <th>Senator reg</th>
                                <th>Diputies</th>
                                <th>Parlament Andin</th>
                                {{-- Lógica condicional para las columnas de votos --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($parties as $party)
                                <tr>
                                    <td>{{ $n++ }}</td>
                                    <td>{{ $party->name }}</td>
                                    <td class="alert alert-primary"><input type="number" name="votes[{{ $party->id }}][votes_president]"
                                            class="form-control no-enter president" min="0"
                                            value="{{ $votos->where('party_id', $party->id)->first()->votes_president ?? '' }}">
                                    </td>
                                    <td class="alert alert-danger"><input type="number" name="votes[{{ $party->id }}][votes_senatornac]"
                                            class="form-control no-enter senatornac" min="0"
                                            value="{{ $votos->where('party_id', $party->id)->first()->votes_senatornac ?? '' }}">
                                    </td>
                                    <td class="alert alert-warning"><input type="number" name="votes[{{ $party->id }}][votes_senatorreg]"
                                            class="form-control no-enter senatorreg" min="0"
                                            value="{{ $votos->where('party_id', $party->id)->first()->votes_senatorreg ?? '' }}">
                                    </td>
                                    <td class="alert alert-success"><input type="number" name="votes[{{ $party->id }}][votes_diputies]"
                                            class="form-control no-enter diputies" min="0"
                                            value="{{ $votos->where('party_id', $party->id)->first()->votes_diputies ?? '' }}">
                                    </td>
                                    <td class="alert alert-info"><input type="number" name="votes[{{ $party->id }}][votes_andino]"
                                            class="form-control no-enter andino" min="0"
                                            value="{{ $votos->where('party_id', $party->id)->first()->votes_andino ?? '' }}">
                                    </td>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Add your form fields here -->
                    <button type="submit" class="btn btn-primary" id="enviar">Register votes</button>
                </form>

            </div>
        @else
            <div class="alert alert-danger" role="alert">
                <strong>Error!</strong> No polling station found with the provided number.
            </div>
        @endif
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
    <script>
        // Evitar que el formulario se envíe al presionar Enter, cambiar foco al siguiente input y que seleccione el contenido

        document.querySelectorAll('.no-enter').forEach(input => {
            input.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const formElements = Array.from(document.querySelectorAll('.no-enter'));
                    const currentIndex = formElements.indexOf(this);
                    const nextIndex = (currentIndex + 1) % formElements.length;
                    formElements[nextIndex].focus();
                    formElements[nextIndex].select();
                }
            });
        });
    </script>

    <script>
        //Crear funcion, asociada a button enviar, que sume totales de votos y que no envie datos al controlador si suma excede total de votos por mesa
        document.getElementById('miFormulario').addEventListener('submit', function(event) {
            let sumap = 0;
            let sumas = 0;
            let sumasr = 0;
            let sumad = 0;
            let sumaa = 0;
            let electores = {{ $mesa->electors ?? 0 }};
            let emitidos1 = document.getElementById('emitidospre').value;
            let emitidos = Number(emitidos1);
           
            if (emitidos > electores) {
                alert('The number of votes cast cannot be greater than the number of voters.!!');
                event.preventDefault(); // Detiene el envío
                return;
            }
            let maximo = Math.min(electores, emitidos);
            document.querySelectorAll('.president').forEach(input => {
                sumap += parseInt(input.value) || 0;
            });
            if (sumap != maximo) { // Límite ejemplo
                alert('Please verify that the sum of votes for President is: ' + maximo);
                event.preventDefault(); // Detiene el envío
                return;
            }

            document.querySelectorAll('.senatornac').forEach(input => {
                sumas += parseInt(input.value) || 0;
            });
            if (sumas != maximo) { // Límite ejemplo
                alert('Please verify that the sum of votes for senatorNac is: ' + maximo);
                event.preventDefault(); // Detiene el envío
                return;
            }

            document.querySelectorAll('.senatorreg').forEach(input => {
                sumasr += parseInt(input.value) || 0;
            });
            if (sumasr != maximo) { // Límite ejemplo
                alert('Please verify that the sum of votes for SenatorReg is: ' + maximo);
                event.preventDefault(); // Detiene el envío
                return;
            }

            document.querySelectorAll('.diputies').forEach(input => {
                sumad += parseInt(input.value) || 0;
            });
            if (sumad != maximo) { // Límite ejemplo
                alert('Please verify that the sum of votes for Diputies are: ' + maximo);
                event.preventDefault(); // Detiene el envío
                return;
            }

            document.querySelectorAll('.andino').forEach(input => {
                sumaa += parseInt(input.value) || 0;
            });
            if (sumaa != maximo) { // Límite ejemplo
                alert('Please verify that the sum of votes for Parlament Andin is: ' + maximo);
                event.preventDefault(); // Detiene el envío
                return;
            }
        });
    </script>
    <script>
        function transferirValor() {
            // 1. Obtener el valor del input1
            var valor = document.getElementById('emitidospre').value;
            // 2. Asignar el valor al input2
            document.getElementById('emitidos').value = valor;
        }
    </script>

@stop
