<section class="mt-3">
    <div class="container-fluid">
        <button id="btnADD" onclick="add(this)" class="btn position-fixed rounded-circle btn-primary text-uppercase" type="button"><i class="fas fa-plus"></i></button>
        
        <div id="carouselExampleIndicators" class="carousel slide wrapper-slider" data-ride="carousel">
            <ol class="carousel-indicators">
                @for($i = 0 ; $i < count($data['sliders']) ; $i++)
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="@if($i == 0) active @endif"></li>
                @endfor
            </ol>
            <div class="carousel-inner">
                @for($i = 0 ; $i < count($data['sliders']) ; $i++)
                <div class="carousel-item @if($i == 0) active @endif">
                    <img class="d-block w-100" src="{{asset('' . $data['sliders'][$i]['image']['i'])}}" >
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
        <div style="display: none;" id="wrapper-form" class="mt-2">
            <div class="card">
                <div class="card-body">
                    <button onclick="remove(this)" type="button" class="close position-absolute" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button class="btn btn-success px-5 text-uppercase d-block mx-auto mb-4"><i class="fas fa-save ml-3"></i></button>
                        <div class="container-form"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-2" id="wrapper-tabla">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0" id="tabla"></table>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    window.pyrus = new Pyrus("slider", null, src);
    window.section = "{{ $data['seccion'] }}";
    window.elementos = @json($data["sliders"]);

    formSubmit = function(t) {
        let idForm = t.id;
        let formElement = document.getElementById( idForm );

        let formData = new FormData( formElement );
        formData.append( "ATRIBUTOS", JSON.stringify(
            [
                { DATA: window.pyrus.objetoSimple, TIPO: "U" },
            ]
        ));
        for( let x in CKEDITOR.instances )
            formData.set( x , CKEDITOR.instances[ `${x}` ].getData() );
        formSave( t , formData );
    };
    /** ------------------------------------- */
    add = function(t, id = 0, data = null) {
        let btn = $(t);
        if(btn.is(":disabled"))
            btn.removeAttr("disabled");
        else
            btn.attr("disabled",true);
        $("#wrapper-form").toggle(800,"swing");

        $("#wrapper-tabla").toggle("fast");

        if(id != 0) {
            action = `{{ url('/adm/${window.pyrus.name}/update/${id}') }}`;
            method = "PUT";
        } else {
            action = `{{ url('/adm/${window.pyrus.name}') }}`;
            method = "POST";
        }
        window.pyrus.show( CKEDITOR , `{{ asset('/') }}` , data );

        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
        $("#form").attr("method",method);
    };
    /** ------------------------------------- */
    erase = function(t, id) {
        window.pyrus.delete( t , { title : "ATENCIÓN" , body : "¿Eliminar registro?" } , `{{ url('/adm/${window.pyrus.name}/delete') }}` , id );
    };
    /** ------------------------------------- */
    remove = function(t) {
        alertify.confirm( "ATENCIÓN" , "¿Cerrar sin guardar registro?",
            function() {
                window.pyrus.clean( CKEDITOR );

                add( $( "#btnADD" ) );
            },
            function() {}
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
    /** ------------------------------------- */
    edit = function(t, id) {
        $(t).attr("disabled",true);
        window.pyrus.one( `{{ url('/adm/${window.pyrus.name}/${id}/edit') }}`, function( res ) {
            $(t).removeAttr("disabled");
            add($("#btnADD"),parseInt(id),res.data);
        } );
    };
    clone = function( t, id ) {

    };
    shortcut.add("Alt+Ctrl+S", function () {
        if($("#form").is(":visible")) {
            $("#form").submit();
        }
    }, {
        "type": "keydown",
        "propagate": true,
        "target": document
    });
    
    shortcut.add("Alt+Ctrl+N", function () {
        if(!$("#form").is(":visible")) {
            $("#btnADD").click();
        } else {
            remove( null );
        }
    }, {
        "type": "keydown",
        "propagate": true,
        "target": document
    });
    /** ------------------------------------- */
    init = function( callbackOK ) {
        $( "#form .container-form" ).html(window.pyrus.formulario());
        $( "#wrapper-tabla > div" ).html(window.pyrus.table([ {NAME:"ACCIONES", COLUMN: "acciones", CLASS: "text-center", WIDTH:"150px"} ]));
        
        
        window.pyrus.elements( $( "#tabla" ) , `{{ asset('/') }}` , window.elementos , [ "e" , "d" ] );
        window.pyrus.editor( CKEDITOR );
        callbackOK.call(this,null);
    }
    /** */
    init( function() {
        
    });
</script>
@endpush