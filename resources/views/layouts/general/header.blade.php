<div id="menuNav" class="modal fade menuNav" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-top-0 border-left-0 border-bottom-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Menú</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    @if(!auth()->guard('client')->check())
                    <li class="list-group-item text-uppercase"><a href="{{ route('index') }}">Home</a></li>
                    <li class="list-group-item text-uppercase"><a href="{{ route('empresa') }}">Empresa</a></li>
                    <li class="list-group-item text-uppercase"><a href="{{ route('productos') }}">Productos</a></li>
                    <li class="list-group-item text-uppercase"><a href="{{ route('descargas') }}">Descargas</a></li>
                    <li class="list-group-item text-uppercase"><a href="{{ route('presupuesto') }}">Solicitud de cotización</a></li>
                    @endif
                    <li class="list-group-item text-uppercase"><a href="{{ route('contacto') }}">Contacto</a></li>
                    
                    <li class="list-group-item text-uppercase d-flex justify-content-center border-top-0">
                        <i class="fas fa-map-marker-alt mr-2"></i> <a href="https://www.google.com/maps?ll=-34.603676,-58.375119&z=16&t=m&hl=es&gl=AR&mapclient=embed&cid=14343660536398655397" target="blank">{!! $data["empresa"]["domicilio"]["calle"] !!} {!! $data["empresa"]["domicilio"]["altura"] !!} @if(!empty($data["empresa"]["domicilio"]["cp"])) ({{ $data["empresa"]["domicilio"]["cp"] }})@endif<br>{{ $data["empresa"]["domicilio"]["provincia"] }}@if(!empty($data["empresa"]["domicilio"]["localidad"])) - {{ $data["empresa"]["domicilio"]["localidad"] }}@endif Argentina</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<header class="w-100 position-fixed font-opensans">
    <div class="private">
        <div class="container">
            <div>
                @php
                $visible = $data["telefono"][ "telefono" ];
                $telefono = $data["telefono"][ "telefono" ];
                if( !empty( $data["telefono"][ "visible" ] ) )
                    $visible = $data["telefono"][ "visible" ];
                @endphp
                @if( $data["telefono"][ "is_link" ] )
                    <a title="{{ $visible }}" class="text-truncate d-block" href="tel:{{ $telefono }}"><i class="fas fa-phone-alt mr-2"></i>{{ $visible }}</a>
                @else
                    <i class="fas fa-phone-alt mr-2"></i>{{ $visible }}
                @endif
            </div>
            <ul class="mb-0 list-unstyled menu d-flex justify-content-end align-items-center">
                <li class="hidden-tablet">
                    @if(auth()->guard('client')->check())
                    <div class="d-inline-block dropdown">
                        <p class="private" style="padding: .25rem .5rem">
                            Bienvenido, {{ auth()->guard('client')->user()["name"] }} (<a href="{{ URL::to('salir') }}">Cerrar sesión</a>)<i class="fas fa-user-circle"></i>
                        </p>
                    </div>
                    @else
                    <div class="d-inline-block dropdown">
                        <button id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link btn-sm private d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Zona Cliente<i class="fas fa-user-circle"></i>
                        </button>
                        <ul class="submenu list-unstyled pb-0 rounded-0 shadow-sm rounded dropdown-menu dropdown-menu-right" id="login" aria-labelledby="dropdownMenuButton">
                            <li class="rounded-0">
                                <div class="p-3">
                                    <form id="formLogueo" action="{{ url('/cliente/acceso') }}" method="post">
                                        {{ csrf_field() }}
                                        <div class="contenedorForm w-100" id="form_formulario_login">
                                            <div class="row justify-content-center align-items-center">
                                                <div class="col-12">
                                                    <input required value="" name="username" id="username" class="form-control rounded-0 border-left-0 border-right-0 border-top-0 texto-text" type="text" placeholder="Usuario" required>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center align-items-center mt-3">
                                                <div class="col-12">
                                                    <input required value="" name="password" id="password" class="form-control rounded-0 border-left-0 border-right-0 border-top-0 texto-text" type="password" placeholder="Contraseña" required>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary mx-auto px-5 d-block mt-3 text-uppercase" type="submit">ingresar</button>
                                    </form>
                                </div>
                            </li>
                            {{--<li class="bg-light text-center rounded-0 border-top bg-light py-2">
                                <a href="{{ route('olvide') }}" class="text-primary">Olvidé mi contraseña</a>
                            </li>--}}
                        </ul>
                    </div>
                    @endif
                </li>
            </ul>
        </div>
    </div>
    <nav class="navbar navbar-light p-0 shadow-sm">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100 position-relative" style="min-height: 88px;">
                @if(auth()->guard('client')->check())
                <a style="left: 0; " class="navbar-brand" href="{{ route('client.descargas') }}">
                    <img style="width: 177px;" onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($data['empresa']['images']['logo']['i']) }}?t=<?php echo time(); ?>" />
                </a>
                @else
                <a style="left: 0; " class="navbar-brand" href="{{ route('index') }}">
                    <img style="width: 177px;" onError="this.src='{{ asset('images/general/no-img.png') }}'" src="{{ asset($data['empresa']['images']['logo']['i']) }}?t=<?php echo time(); ?>" />
                </a>
                @endif
                <button class="navbar-toggler position-absolute rounded-0 bg-white show-tablet" type="button" data-toggle="modal" data-target="#menuNav" style="right:0;">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="d-flex flex-column pt-0 pr-0" style="">
                    <ul id="ulNavFixed" class="mb-0 list-unstyled menu d-flex justify-content-end align-items-center py-3">
                        @if(auth()->guard('client')->check())
                        <li class="hidden-tablet"><a href="{{ route('client.descargas') }}">Descargas</a></li>
                        @else
                        <li class="hidden-tablet"><a href="{{ route('empresa') }}">Empresa</a></li>
                        <li class="hidden-tablet position-relative">
                            <a href="{{ route('productos') }}" @if(Request::is('familia*') || Request::is('categoria*') || Request::is('producto*')) class="active" @endif>Productos</a>
                            <ul class="mb-0 list-unstyled submenu position-absolute">
                                @foreach( $data[ 'menu' ] AS $m )
                                <li class="hidden-tablet position-relative">
                                    <a href="{{ URL::to('/familia/' . $m[ 'url' ] . '/' . $m[ 'id' ]) }}">{{ $m[ 'name' ] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="hidden-tablet"><a href="{{ route('descargas') }}">Descargas</a></li>
                        <li class="hidden-tablet"><a href="{{ route('presupuesto') }}">Solicitud de presupuesto</a></li>
                        <li class="hidden-tablet"><a href="{{ route('contacto') }}">Contacto</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>