<div class="wrapper-contacto">
    <div class="mapa bg-white">
        {!! $data["empresa"]["domicilio"]["mapa"] !!}
    </div>
    <div class="" style="margin-top: -8px">
        <div class="container py-5">
            <div class="row py-3">
                <div class="col-12 col-md-4 info">
                    <ul class="list-unstyled info mb-0">
                        <li class="d-flex">
                            <i class="mr-4 icono fas fa-map-marker-alt"></i>
                            <div style="width: calc(100% - 50px)">
                                <p class="mb-0">{!! $data["empresa"]["domicilio"]["calle"] !!} {!! $data["empresa"]["domicilio"]["altura"] !!}@if(!empty($data["empresa"]["domicilio"]["cp"])) ({{ $data["empresa"]["domicilio"]["cp"] }})@endif<br>{{ $data["empresa"]["domicilio"]["provincia"] }}@if(!empty($data["empresa"]["domicilio"]["localidad"])) - {{ $data["empresa"]["domicilio"]["localidad"] }}@endif  {{ $data["empresa"]["domicilio"]["pais"] }}</p>
                            </div>
                        </li>
                    </ul>
                    <ul class="mt-3 list-unstyled info mb-0">
                        @php
                        $A_tel = $A_wha = [];
                        foreach($data["empresa"]["telefono"] AS $t) {
                            if($t["tipo"] == "tel")
                                $A_tel[] = $t["telefono"];
                            else
                                $A_wha[] = $t["telefono"];
                        }
                        @endphp
                        <li class="d-flex">
                            <i class="mr-4 icono fas fa-phone-volume"></i>
                            <div style="width: calc(100% - 50px)">
                                @foreach($A_tel AS $t)
                                    <a title="{{$t}}" class="text-truncate d-block" href="tel:{!!$t!!}">{!!$t!!}</a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                    <ul class="mt-3 list-unstyled info mb-0">
                        <li class="d-flex">
                            <i class="mr-4 icono far fa-envelope"></i>
                            <div style="width: calc(100% - 50px)">
                                @foreach($data["empresa"]["email"] as $e)
                                    <a title="{{$e}}" class="text-truncate d-block" href="mailto:{!!$e!!}" target="blank">{!!$e!!}</a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-md-8" id="contacto-container">
                    <form action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-6">
                                <input placeholder="Nombre" required type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                                <input placeholder="Teléfono" type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 col-md-12 col-lg-6">
                                <input placeholder="Correo electrónico" required type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                                <input placeholder="Empresa" type="text" name="empresa" class="form-control" value="{{ old('empresa') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <textarea name="mensaje" class="form-control" id="" placeholder="Mensaje">{{ old('mensaje') }}</textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="overflow-x">
                                    <div class="g-recaptcha" data-sitekey="6LfTp68UAAAAANSUBbmUnW4tFWyKPCiE-y_pRf-p"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terminos" value="1" id="terminos">
                                    <label class="form-check-label" for="terminos" style="font-size: 15px;">
                                        Acepto los términos y condiciones de privacidad
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-center">
                                <button class="mt-2 btn btn-primary px-5" type="submit">Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>
@push('scripts')
<script src='https://www.google.com/recaptcha/api.js'></script>
@endpush