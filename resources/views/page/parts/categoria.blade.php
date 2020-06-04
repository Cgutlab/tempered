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
                <div class="wrapper-familia mt-0 font-opensans">
                    <div class="row">
                        @if( isset( $data[ "categoria" ] ) )
                        @foreach( $data[ "categoria" ]->productos AS $f )
                        <div class="col-12 col-md-6 col-lg-4 my-3">
                            <a href="{{ URL::to('/producto/' . $f[ 'url' ] . '/' . $f[ 'id' ]) }}" class="border bg-white d-block familia">
                                @php
                                $image = "";
                                $images = $f->images;
                                if( count( $images ) > 0 )
                                    $image = $images[0][ "image" ]['i'];
                                @endphp
                                <img src="{{ asset( $image ) }}" class="w-100 d-block" alt="" srcset="">
                                <p class="title p-4 text-center">{{ $f[ "name" ] }}</p>
                            </a>
                        </div>
                        @endforeach                        
                        @else
                            @foreach( $data[ "familia" ]->categorias AS $f )
                            <div class="col-12 col-md-6 col-lg-4 my-3">
                                <a href="{{ URL::to('/categoria/' . $f->familia[ 'url' ] . '/' . $f[ 'url' ] . '/' . $f[ 'id' ]) }}" class="border bg-white d-block familia">
                                    <img src="{{ asset( $f[ 'image' ][ 'i' ] ) }}" class="w-100 d-block" alt="" srcset="">
                                    <p class="title p-4 text-center">{{ $f[ "name" ] }}</p>
                                </a>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>