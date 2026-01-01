@extends('adminlte::page')

@section('title', 'List of Parties by District')

@section('content_header')
    <h1>Parties of district: {{ $district->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.districtsparty.update', $district->id) }}">
                @csrf
                @method('PUT')
                @if ($parties->count() > 0)
                    @foreach ($parties as $party)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="parties[]" value="{{ $party->id }}" disabled
                                id="party{{ $party->id }}" checked>
                            <label class="form-check-label" for="party{{ $party->id }}">
                                {{ $party->name }}
                            </label>
                        </div>
                    @endforeach
                @else
                    <div class="form-check">
                        <label class="form-check-label">
                            No political parties assigned to this district.
                        </label>
                    </div>

                @endif

                <div class="mt-3">
                    <label for="escanios">Number of Seats:</label>
                    <input type="number" class="form-control @error('escanios') is-invalid @enderror" id="escanios" name="escanios" min="5"
                        value="{{ $district->escanios ?? '' }}">
                    @error('escanios')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt-4">Update</button>
                <button class="btn btn-secondary mt-4 ml-4" onclick="window.history.back()">Back</button>
            </form>

        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
