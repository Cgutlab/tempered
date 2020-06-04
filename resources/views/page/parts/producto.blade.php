<div class="wrapper-categoria font-opensans py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                <ul class="list-group list-group-flush menu accordion" id="accordionMenu">
                @foreach( $data[ "familias" ] AS $f )
                    <li class="list-group-item menu-familia px-2" id="heading_{{ $f[ 'id' ]}}">
                        <div class="d-flex align-items-center justify-content-between collapsed" data-toggle="collapse" data-target="#collapse_{{ $f[ 'id' ]}}" @if( $data[ 'familia' ][ 'id' ] == $f[ 'id' ] ) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="collapse_{{ $f[ 'id' ]}}">
                            <a href="{{ URL::to('/familia/' . $f[ 'url' ] . '/' . $f[ 'id' ]) }}">
                                {{ $f[ "name" ] }}
                            </a>
                            <i class="fas fa-angle-right"></i>
                        </div>
                        <ul class="list-group list-group-flush submenu collapse @if( $data[ 'familia' ][ 'id' ] == $f[ 'id' ] ) show @endif" id="collapse_{{ $f[ 'id' ]}}" aria-labelledby="heading_{{ $f[ 'id' ]}}" class="collapse" data-parent="#accordionMenu">
                            @foreach( $f->categorias()->orderBy('order')->get() AS $c )
                            <li class="list-group-item menu-categoria px-2">
                                @if( isset( $data[ "categoria" ] ) )
                                <a href="{{ URL::to('/categoria/' . $f[ 'url' ] . '/' . $c[ 'url' ] . '/' . $c[ 'id' ]) }}" class="@if($data[ 'categoria' ][ 'id' ] == $c[ 'id' ]) active @endif">
                                    {{ $c[ "name" ] }}
                                </a>
                                @else
                                <a href="{{ URL::to('/categoria/' . $f[ 'url' ] . '/' . $c[ 'url' ] . '/' . $c[ 'id' ]) }}">
                                    {{ $c[ "name" ] }}
                                </a>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="col-12 col-md-8 col-lg-9">
                <div class="wrapper-producto mt-0 font-opensans">
                    <div class="row">
                        <div class="col-12">
                            <ol class="breadcrumb px-0 py-3 mb-0 bg-white rounded-0">
                                <li class="breadcrumb-item"><a href="{{ route('productos') }}">Productos</a></li>
                                <li class="breadcrumb-item"><a href="{{ URL::to('familia/' . $data[ 'familia' ][ 'url' ] . '/' . $data[ 'familia' ][ 'id' ]) }}">{{ $data[ 'familia' ][ 'name' ]}}</a></li>
                                <li class="breadcrumb-item"><a href="{{ URL::to('categoria/' . $data[ 'familia' ][ 'url' ] . '/' . $data[ 'categoria' ][ 'url' ] . '/' . $data[ 'categoria' ][ 'id' ]) }}">{{ $data[ 'categoria' ][ 'name' ]}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $data[ 'producto' ][ 'name' ] }}</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            @php
                            $image = $data[ 'producto' ]->images;
                            @endphp
                            <div id="carouselExampleIndicators" class="carousel border p-3 slide wrapper-slider" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    @for($i = 0 ; $i < count($image) ; $i++)
                                        <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="@if($i == 0) active @endif"></li>
                                    @endfor
                                </ol>
                                <div class="carousel-inner">
                                    @for($i = 0 ; $i < count($image) ; $i++)
                                    <div class="carousel-item @if($i == 0) active @endif">
                                        <img class="d-block w-100" src="{{asset($image[$i]['image']['i'])}}" >
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <h3 class="title">{{ $data[ 'producto' ][ 'name' ] }}</h3>
                            <p class="code">{{ $data[ 'producto' ][ 'code' ] }}</p>
                            <div class="mt-3">{!! $data[ 'producto' ][ 'charact' ] !!}</div>

                            <div class="row mt-3">
                                @if( !empty( $data[ 'producto' ][ 'file' ] ))
                                <div class="col-12 col-md">
                                    <a download href="{{ asset( $data[ 'producto' ][ 'file' ][ 'i' ] ) }}" class="btn btn-outline-primary text-uppercase btn-block text-truncate">ficha técnica</a>
                                </div>
                                @endif
                                <div class="col-12 col-md">
                                    <button onclick="consultar(this,{{$data['producto']['id']}});" class="btn btn-outline-primary text-uppercase btn-block text-truncate">consultar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push( "scripts" )
<script>
    window.producto = @json($data["producto"]);
    window.categoria = @json($data['categoria']);
    window.familia = @json($data['familia']);
    consultar = function( t , id ) {
        localStorage.setItem( "productoID" , id );
        localStorage.setItem( "producto" , JSON.stringify(window.producto) );
        localStorage.setItem( "categoria" , JSON.stringify(window.categoria) );
        localStorage.setItem( "familia" , JSON.stringify(window.familia) );
        window.location = '{{ route('presupuesto') }}';
    };
</script>
@endpush