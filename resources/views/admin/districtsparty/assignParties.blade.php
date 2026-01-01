@extends('adminlte::page')

@section('title', 'Parties Assignment')

@section('content_header')
    <h1>Partidos del distrito: {{ $district->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.districtsparty.store', ['district_id' => $district->id]) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="parties">Select Political Parties </label>
                    @foreach ($parties as $party)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="parties[]" value="{{ $party->id }}"
                                id="part-{{ $party->id }}"
                                {{ $district->parties->contains($party->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="party{{ $party->id }}">
                                {{ $party->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <div>
                   <button type="submit" class="btn btn-primary">Assign Parties</button>
                   <button class="btn btn-secondary ml-4" onclick="window.history.back()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
