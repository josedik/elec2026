@extends('adminlte::page')

@section('title', 'Register votes')

@section('content_header')
    <h1>Register Votes</h1>
@stop

@section('content')
    <x-adminlte-card title="Formulario de Escrutinio" icon="fas fa-vote-yea" collapsible removable>

        <form id="sinEnterForm" action="{{ route('admin.mesas.storeVotes', $mesa) }}" method="POST">
            @csrf
            <input type="hidden" name="isCapital" value="{{ $isCapital }}">

            <div class="d-flex justify-content-end">
                <div class="mr-2">
                    <x-adminlte-alert theme="success" title="Mesa: {{ $mesa->code ?? '' }}" />
                </div>
                <div class="ml-2">
                    <x-adminlte-alert theme="info" title="Electors: {{ $mesa->electors ?? '' }}">

                    </x-adminlte-alert>
                </div>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Partido Político</th>
                        {{-- Lógica condicional para las columnas de votos --}}
                        @if ($isCapital)
                            <th>Votos Provinciales</th>
                        @else
                            <th>Votos Provinciales</th>
                            <th>Votos Distritales</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($parties->count() > 0)
                        @foreach ($parties as $party)
                            <tr>
                                <td>{{ $party->name }}</td>
                                @if ($isCapital)
                                    <td>
                                        <x-adminlte-input name="votes_province[{{ $party->id }}]" type="number" min=0
                                            placeholder="Provincial"
                                            value="{{ $mesa->parties()->find($party->id)->pivot->votes_province ?? '' }}"
                                            enable-old-support />
                                        <script>
                                            document.querySelector('input[name="votes_province[{{ $party->id }}]"]').addEventListener('change', function() {
                                                let total = 0;
                                                document.querySelectorAll('input[name^="votes_province["]').forEach(input => {
                                                    if (input.name !== 'votes_province[total]') {
                                                        total += parseInt(input.value) || 0;
                                                    }
                                                });
                                                document.getElementById('votes_province[total]').value = total;
                                            });
                                        </script>
                                    </td>
                                @else
                                    <td>
                                        <x-adminlte-input name="votes_province[{{ $party->id }}]" type="number" min=0
                                            value="{{ $mesa->parties()->find($party->id)->pivot->votes_province ?? '' }}"
                                            enable-old-support placeholder="Provincial" enable-old-support />
                                    </td>
                                    <script>
                                        document.querySelector('input[name="votes_province[{{ $party->id }}]"]').addEventListener('change', function() {
                                            let total = 0;
                                            document.querySelectorAll('input[name^="votes_province["]').forEach(input => {
                                                if (input.name !== 'votes_province[total]') {
                                                    total += parseInt(input.value) || 0;
                                                }
                                            });
                                            document.getElementById('votes_province[total]').value = total;
                                        });
                                    </script>
                                    <td>
                                        <x-adminlte-input name="votes_district[{{ $party->id }}]" type="number" min=0
                                            value="{{ $mesa->parties()->find($party->id)->pivot->votes_district ?? '' }}"
                                            enable-old-support placeholder="Distrital" enable-old-support />
                                    </td>
                                    <script>
                                        document.querySelector('input[name="votes_district[{{ $party->id }}]"]').addEventListener('change', function() {
                                            let total = 0;
                                            document.querySelectorAll('input[name^="votes_district["]').forEach(input => {
                                                if (input.name !== 'votes_district[total]') {
                                                    total += parseInt(input.value) || 0;
                                                }
                                            });
                                            document.getElementById('votes_district[total]').value = total;
                                        });
                                    </script>
                                @endif
                            </tr>
                        @endforeach
                        @if ($isCapital)
                            <tr>
                                <th>Total</th>
                                <th>
                                    <x-adminlte-input id="votes_province[total]" name="votes_province[total]" type="number"
                                        min=0 value="{{ $mesa->parties()->sum('votes_province') ?? '0' }}" readonly />
                                </th>
                            </tr>
                        @else
                            <tr>
                                <th>Total</th>
                                <th>
                                    <x-adminlte-input id="votes_province[total]" name="votes_province[total]" type="number"
                                        min=0 value="{{ $mesa->parties()->sum('votes_province') ?? '0' }}" readonly />
                                </th>
                                <th>
                                    <x-adminlte-input id="votes_district[total]" name="votes_district[total]" type="number"
                                        min=0 value="{{ $mesa->parties()->sum('votes_district') ?? '0' }}" readonly />
                                </th>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <th>No hay Partidos Políticos para este distrito</th>
                        </tr>

                    @endif
                </tbody>
            </table>
            @if ($parties->count() > 0)
                <x-adminlte-button class="mr-auto" type="submit" label="Save results" theme="success" icon="fas fa-save" />
            @endif
            <button class="btn btn-secondary ml-4" onclick="window.history.back()"><i
                    class="fas fa-arrow-left mr-2"></i>Volver</button>


        </form>
    </x-adminlte-card>
@stop

@section('css')

@stop

@section('js')
    @if (session('alert'))
        <script>
            Swal.fire(@json(session('alert')))
        </script>
    @endif
    <script>
        // Evitar el envío del formulario al presionar Enter
        document.getElementById('sinEnterForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                // Mover el foco al siguiente campo de entrada y seleccionar su contenido

                const inputs = Array.from(document.querySelectorAll('input[type="number"]'));
                const currentIndex = inputs.findIndex(input => input === event.target);
                if (currentIndex !== -1 && currentIndex < inputs.length - 1) {
                    inputs[currentIndex + 1].focus();
                    inputs[currentIndex + 1].select();
                }
            }
        });
    </script>
@stop
