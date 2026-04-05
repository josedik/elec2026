@extends('adminlte::page')

@section('title')

@section('content_header')
    <h1>Final Reports</h1>
    
@stop

@section('content')
    <div class="row row-cols-1">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>President</h3>
                    <h3>Perú</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <a href="{{ route('admin.votos.verResultados', ['cargo' => 'president']) }}" class="small-box-footer">View results <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>National </h3>
                    <h3>Senators</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.votos.verResultados', ['cargo' => 'senatornac']) }}" class="small-box-footer" >View results <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Regional</h3>
                    <h3>Senators</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.votos.verResultados', ['cargo' => 'senatorreg']) }}" class="small-box-footer"
                     ">View results <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Diputies</h3>
                    <h3>Regional</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.votos.verResultados', ['cargo' => 'diputies']) }}" class="small-box-footer" 
                     >View results <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>Andean</h3>
                    <h3>Parliament</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.votos.verResultados', ['cargo' => 'andino']) }}" class="small-box-footer">View results <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @can('admin.votos.create')
        <div class="col-lg-3 col-6">
            <div class="small-box bg-light">
                <div class="inner">
                    <h3>Print</h3>
                    <h3>Survey</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="{{ route('admin.votos.verResultados', ['cargo' => 'printSurvey']) }}" class="small-box-footer">Print form survey <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endcan
    </div>
@stop
