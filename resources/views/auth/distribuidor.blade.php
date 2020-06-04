@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/adm/menu.css') }}" rel="stylesheet">
@endpush

@section('headTitle', config('app.name') . ' :: Administración')

@section('content')
<div class="wrapper flex-column">
    <header class="app-header navbar bg-white border-bottom position-fixed shadow-sm w-100">
        <div class="d-flex align-items-center w-100">
            <nav class="navbar justify-content-between w-100 navbar-expand-lg navbar-light p-0">
                <a class="navbar-brand" href="#">ADMIN</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle pr-5" href="#" id="navbarDropdownMenuUsuario" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span><i class="fas fa-user-circle mr-2"></i>{{Auth::user()["name"]}}</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUsuario">
                                <a class="dropdown-item" href="{{ route('empresa.usuarios.datos') }}"><i class="fas fa-database mr-2"></i>Mis Datos</a>
                                <a class="dropdown-item" href="{{ route('adm.logout') }}"><i class="fas text-danger fa-power-off mr-2"></i>Salir</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('empresa.usuarios.index') }}"><i class="fas fa-user-friends mr-2"></i>Usuarios</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-uppercase pr-5" href="#" id="navbarDropdownMenuEmpresa" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ config('app.name') }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuEmpresa">
                                <a class="dropdown-item" href="{{ route('empresa.datos') }}"><i class="fab fa-centercode mr-2"></i>Datos</a>
                                <a class="dropdown-item" href="{{ route('empresa.form') }}"><i class="fab fa-wpforms mr-2"></i>Email de Formularios</a>
                                <a class="dropdown-item" href="{{ route('empresa.redes.index') }}"><i class='nav-icon fas fa-comment mr-2'></i>Redes Sociales</a>
                                <a class="dropdown-item" href="{{ route('empresa.metadatos.index') }}"><i class='nav-icon fas fa-bullhorn mr-2'></i>Metadatos</a>
                                <a class="dropdown-item" href="{{ route('empresa.terminos') }}"><i class='nav-icon fas fa-clipboard-check mr-2'></i>Términos y condiciones</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div>
                    <button data-toggle="modal" data-target="#modalCombinacion" class="btn btn-link btn-sm" type="button"><i class="fas fa-keyboard"></i></button>
                    <a href="{{ route('index') }}" target="blank">Sitio</a>
                </div>
            </nav>
    </header>
    
    <div class="app-body">
        <!-- Sidebar -->
        <nav id="sidebar">
            @include('layouts.menu')
        </nav>
        <!-- Page Content -->
        <div id="content">
            <nav aria-label="breadcrumb" id="breadcrumb">
                <ol class="breadcrumb rounded-0 border-bottom bg-white">
                    <li class="breadcrumb-item"><a href="{{ route('adm') }}">ADMIN</a></li>
                    @if(!isset($data["breadcrumb"]))
                        <li class="breadcrumb-item active" aria-current="page">{{$data["title"]}}</li>
                    @else
                        {!! $data["breadcrumb"] !!}
                    @endif
                </ol>
            </nav>
            <div class="pb-3">
                @include($data['view'])
            </div>
        </div>
    </div>
</div>
@endsection