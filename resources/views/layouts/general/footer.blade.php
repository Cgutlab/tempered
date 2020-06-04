<footer>
    <div class="container pt-5 pb-3">
        <div class="row justify-content-start">
            <div class="col-12 col-md-6 col-lg-4 d-flex">
                <img class="logo align-self-center" src="{{ asset($data['empresa']['images']['logoFooter']['i']) }}" alt="" srcset="">
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <h3 class="title text-uppercase mb-2">Secciones</h3>
                <ul class="list-unstyled mb-0 menu">
                    @if(auth()->guard('client')->check())
                    <li><a href="{{ route('client.descargas') }}">Descargas</a></li>
                    @else
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li class=""><a href="{{ route('empresa') }}">Empresa</a></li>
                    <li class=""><a href="{{ route('productos') }}">Productos</a></li>
                    <li class=""><a href="{{ route('descargas') }}">Descargas</a></li>
                    <li class=""><a href="{{ route('presupuesto') }}">Solicitar presupuesto</a></li>
                    <li class=""><a href="{{ route('contacto') }}">Contacto</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <h3 class="title text-uppercase mb-2">{{ config('app.name') }}</h3>
                <ul class="list-unstyled info mb-0">
                    <li class="d-flex">
                        <i style="margin-right:10px;" class="icono fas fa-map-marker-alt"></i>
                        <div class="" style="margin-top: -5px; width: calc(100% - 37px)" >
                            <p class="mb-0"><a href="https://goo.gl/maps/4PHPe1oxRTyrSqgZ6" target="blank">{!! $data["empresa"]["domicilio"]["calle"] !!} {!! $data["empresa"]["domicilio"]["altura"] !!} @if(!empty($data["empresa"]["domicilio"]["cp"])) ({{ $data["empresa"]["domicilio"]["cp"] }})@endif<br>{{ $data["empresa"]["domicilio"]["provincia"] }}@if(!empty($data["empresa"]["domicilio"]["localidad"])) - {{ $data["empresa"]["domicilio"]["localidad"] }}@endif {{ $data["empresa"]["domicilio"]["pais"] }}</a></p>
                        </div>
                    </li>
                        @php
                    $A_tel = $A_wha = [];
                    foreach($data["empresa"]["telefono"] AS $t) {
                        if($t["tipo"] == "tel")
                            $A_tel[] = $t;
                        else
                            $A_wha[] = $t["telefono"];
                    }
                    @endphp
                    <li class="d-flex mt-2">
                        <i style="margin-right:10px;" class="icono fas fa-phone-volume"></i>
                        <div class="" style="margin-top: -5px; width: calc(100% - 37px)">
                            @foreach($A_tel AS $t)
                                @php
                                $visible = $t[ "telefono" ];
                                $telefono = $t[ "telefono" ];
                                if( !empty( $t[ "visible" ] ) )
                                    $visible = $t[ "visible" ];
                                @endphp
                                @if( $t[ "is_link" ] )
                                    <a title="{{ $visible }}" class="text-truncate d-block" href="tel:{{ $telefono }}">{{ $visible }}</a>
                                @else
                                    {{ $visible }}
                                @endif
                            @endforeach
                        </div>
                    </li>
                    @if(!empty($A_wha))
                    <li class="d-flex mt-2">
                        <i class="fab fa-whatsapp" style="color:#4DC95C; margin-right:10px;"></i>
                        <div class="" style="margin-top: -5px; width: calc(100% - 37px)">
                            @foreach($A_wha AS $t)
                                <a title="{{$t}}" class="whatsapp text-truncate d-block" href="https://wa.me/{!!$t!!}">{!!$t!!}</a>
                            @endforeach
                        </div>
                    </li>
                    @endif
                    <li class="d-flex mt-2">
                        <i style="margin-right:10px;" class="icono far fa-envelope"></i>
                        <div class="" style="margin-top: -5px; width: calc(100% - 37px)">
                            @foreach($data["empresa"]["email"] as $e)
                                <a title="{{ $e }}" class="text-truncate d-block" href="mailto:{!!$e!!}" target="blank">{!!$e!!}</a>
                            @endforeach
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row border-top border-left-0 border-right-0 border-bottom-0 mt-3">
            <div class="col-12">
                <p class="mb-0 d-flex justify-content-between">
                    <span>© 2019</span>
                    <a href="http://osole.es" style="color:inherit" class="right text-uppercase">by osole</a>
                </p>
            </div>
        </div>
    </div>
</footer>