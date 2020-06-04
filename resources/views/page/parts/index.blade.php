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
<div class="wrapper-home">
    <div class="iconos py-5">
        <div class="container">
            <div class="row font-opensans">
                @foreach($data["contenido"]["data"]["iconos"] AS $i)
                <div class="col-12 col-md-4">
                    <img src="{{ asset($i['image']['i']) }}" class="d-block mx-auto mb-3" alt="" srcset="">
                    <div class="text-center mx-auto text">{!! $i['text'] !!}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if( count( $data[ "productos" ] ) > 0 )
<div class="wrapper-destacado py-5">
    <div class="container py-5">
        <div class="row pb-3">
            <div class="col-12">
                <h3 class="title text-center">Productos destacados</h3>
            </div>
        </div>
        <div class="row">
            @foreach( $data[ "productos" ] AS $p )
                <div class="col-12 col-md-6 col-lg-4">
                    @php
                    $img = "";
                    $images = $p->images;
                    if( count( $images ) > 0 )
                        $img = $images[ 0 ][ "image" ][ "i" ];
                    @endphp
                    <a href="" class="shadow-sm d-block border destacado">
                        <img src="{{ $img }}" class="w-100" onError="this.src='{{ asset('images/general/no-img.png') }}'" alt="{{ $p['name'] }}">
                        <p class="p-3 text-center">{{ $p['name'] }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif