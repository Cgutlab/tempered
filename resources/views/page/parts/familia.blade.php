<div class="wrapper-familia font-opensans py-5">
    <div class="container">
        <div class="row">
            @foreach( $data[ "familias" ] AS $f )
            <div class="col-12 col-md-6 col-lg-4 my-3">
                <a href="{{ URL::to('/familia/' . $f[ 'url' ] . '/' . $f[ 'id' ]) }}" class="border bg-white d-block familia">
                    <img src="{{ asset( $f[ 'image' ][ 'i' ] ) }}" class="w-100 d-block" alt="" srcset="">
                    <p class="title p-4 text-center">{{ $f[ "name" ] }}</p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>