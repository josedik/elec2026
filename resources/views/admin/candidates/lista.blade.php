@extends('adminlte::page')

@section('title', 'Candidates')

@section('content_header')
    <h1>Edit Candidate</h1>
    <small>Enter the candidate's ID number. If the record already exists in the system, the name and surname will be
        displayed automatically; please do not modify them. If the candidate is not yet registered in the system, clicking
        'Update' will create a record with that ID number, name, and surname. To modify this information, use the 'Voters'
        option in the general menu.</small>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                <input type="hidden" name="district_id" value="{{ session('district_id') }}">
                <input type="hidden" name="party_id" value="{{ session('party_id') }}">

                <div class="form-group">
                    <label for="dni">DNI</label>
                    <input type="text" class="form-control @error('dni') is-invalid @enderror" id="dni"
                        name="dni" value="{{ old('dni', $voter->dni ?? '') }}" onkeyup="searchName()" required>
                    @error('dni')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name', $voter->name ?? '') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname"
                        name="surname" value="{{ old('surname', $voter->surname ?? '') }}" required>
                    @error('surname')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
            <form action="{{ route('admin.candidates.edit', session('party_id')) }}" method="GET" class="mt-2">
                <button type="submit" class="btn btn-secondary">Back to Candidate List</button>
            </form>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script src="{{ asset('js/main.js') }}" defer></script>
    @if (session('alert'))
        <script>
            Swal.fire(@json(session('alert')))
        </script>
    @endif
    <script>
        function searchName() {
            var dni = document.getElementById('dni').value;

            if (dni.length < 3) {
                // Clear name and surname fields if DNI is too short
                document.getElementById('name').value = '';
                document.getElementById('surname').value = '';
                return;
            }

            fetch('{{ route('admin.candidates.searchName') }}?dni=' + dni)
                .then(response => response.json())
                .then(data => {
                    if (data.name) {
                        // name y surname muestran valores pero no se modifican
                        document.getElementById('name').readOnly = true;
                        document.getElementById('surname').readOnly = true;
                        document.getElementById('name').value = data.name;
                        document.getElementById('surname').value = data.surname;
                    } else {
                        document.getElementById('name').readOnly = false;
                        document.getElementById('surname').readOnly = false;
                        document.getElementById('name').value = '';
                        document.getElementById('surname').value = '';
                    }
                })
                .catch(error => {
                    console.error('Error fetching name:', error);
                });
                //fin fetch

        }
    </script>

@stop
