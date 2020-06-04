<section class="mt-3">
    <div class="container-fluid">
        <button id="btnADD" onclick="add(this)" class="btn position-fixed rounded-circle btn-primary text-uppercase" type="button"><i class="fas fa-plus"></i></button>

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
        <div class="mt-2 d-flex">
            <div>
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn btn-info rounded-0" disabled><i class="far fa-clone"></i></button>
                <span class="ml-2"><strike>Copiar elemento</strike></span>
            </div>
            <div class="ml-4">
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button>
                <span class="ml-2">Editar parte de descarga</span>
            </div>
            <div class="ml-4">
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button>
                <span class="ml-2">Eliminar parte de descarga</span>
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
    window.pyrus = new Pyrus("descargas", null, src);
    window.elementos = @json($data["descargas"]);
    window.descarga = @json($data["descarga"]);

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
        $(`#${window.pyrus.name}_descarga_id`).val( window.descarga.id );
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
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $( "#form .container-form" ).html(window.pyrus.formulario());
        $( "#wrapper-tabla > div.card-body" ).html(window.pyrus.table([ {NAME:"ACCIONES", COLUMN: "acciones", CLASS: "text-center", WIDTH:"150px"} ]));
        
        
        window.pyrus.elements( $( "#tabla" ) , `{{ asset('/') }}` , window.elementos , [ "e" , "d" ] );
        window.pyrus.editor( CKEDITOR );
        callbackOK.call(this,null);
    }
    /** */
    init( function() {
        
    });
</script>
@endpush