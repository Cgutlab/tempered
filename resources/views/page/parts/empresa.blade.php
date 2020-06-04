<div id="carouselExampleIndicators" class="carousel slide wrapper-slider" data-ride="carousel">
    <ol class="carousel-indicators">
        @for($i = 0 ; $i < count($data['sliders']) ; $i++)
            <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="@if($i == 0) active @endif"></li>
        @endfor
    </ol>
    <div class="carousel-inner">
        @for($i = 0 ; $i < count($data['sliders']) ; $i++)
        <div class="carousel-item @if($i == 0) active @endif">
            <div style="height: 500px; background: url({{asset($data['sliders'][$i]['image']['i'])}}) center center no-repeat; background-size: cover"></div>
            @if(!empty($data['sliders'][$i]['text']))
            <div class="carousel-caption position-absolute w-100 h-100" style="top: 0; left: 0;">
                <div class="container position-relative h-100 w-100 d-flex align-items-center justify-content-start">
                    <div class="texto">
                        {!! $data['sliders'][$i]['text'] !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endfor
    </div>
</div>
<div class="wrapper-empresa py-4 font-opensans" style="background-image:url({{ asset($data['contenido']['data']['background']['i']) }})">
    <div class="container py-5">
        <div class="row">
            <div class="col-12 col-md-6 texto d-flex align-items-center">
                <div>
                    {!! $data["contenido"]["data"]["text"] !!}
                </div>
            </div>
            <div class="col-12 col-md-6 image d-flex align-items-center">
                <img src="{{ asset($data['contenido']['data']['image']['i']) }}" class="w-100" alt="" srcset="">
            </div>
        </div>
    </div>
</div>