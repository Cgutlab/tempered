@php
$datos = [];
foreach( $data[ "descargas" ] AS $d) {
    if( !isset( $datos[ $d[ "category" ] ] ) )
        $datos[ $d[ "category" ] ] = [];
    $datos[ $d[ "category" ] ][] = $d;
}
@endphp

<div class="wrapper-descarga font-opensans py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <h3 class="title">FOLLETOS</h3>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <h3 class="title">MANUAL DE INSTALACIÓN</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach( $datos AS $d => $doc )
            <div class="col-12 col-md-6 col-lg-4">
                <div class="text-center">
                    @foreach( $doc AS $a)
                        <div class="mt-3 descarga">
                            <div style="background-image: url({{ asset($a['cover']['i']) }})" class="d-inlinde-block position-relative">
                                <p class="position-absolute">{{ $a[ 'name' ] }}</p>
                            </div>
                            @php
                            $partes = $a->partes()->where('elim',0)->orderBy('order')->get();
                            @endphp
                            <select name="" id="" onchange="bajar( this );">
                                <option value="" hidden selected>Seleccione un modelo</option>
                                @foreach( $partes AS $p )
                                <option value="{{ $p['id'] }}" data-file="{{ asset( $p['file']['i'] ) }}">{{ $p['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@push( "scripts" )
<script>
    bajar = function( t ) {
        let value = $(t).val();
        let src = $(t).find(`option[value="${value}"]`).data("file");
        let aux = src.split( "/" );
        let link = document.createElement("a");
        link.download = aux[ aux.length - 1 ];
        link.href = src;
        link.click();
        document.body.removeChild(link);
    };
</script>
@endpush