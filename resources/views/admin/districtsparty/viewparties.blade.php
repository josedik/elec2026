@extends('adminlte::page')

@section('title', 'List of Parties by District')

@section('content_header')
    <h1>political parties of the {{ $district->name }} district </h1>
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
                            <input class="form-check-input" type="checkbox" name="parties[]" value="{{ $party->id }}"
                                disabled id="party{{ $party->id }}" checked>
                            <label class="form-check-label" for="party{{ $party->id }}">
                                {{ $party->name }}
                            </label>
                        </div>
                    @endforeach
                @else
                    <div class="form-check">
                        <label class="form-check-label alert alert-warning" for="no-parties">
                            No political parties assigned to this district.
                        </label>
                    </div>

                @endif

                <div class="mt-3">
                    @can('admin.districtsparty.edit')
                        <label for="escanios">Number of Seats:</label>
                        <input type="number" class="form-control @error('escanios') is-invalid @enderror" id="escanios"
                            name="escanios" min="5" value="{{ $district->escanios ?? '' }}">
                        @error('escanios')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    @endcan
                </div>
                @can('admin.districtsparty.edit')
                    <button type="submit" class="btn btn-primary mt-4">Update</button>
                @endcan
                @can('admin.districtsparty.index')
                    <a href="{{ route('admin.districtsparty.index') }}" class="btn btn-secondary mt-4">Back</a>
                @endcan
            </form>

        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
