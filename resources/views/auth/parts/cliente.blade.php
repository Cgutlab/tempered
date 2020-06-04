<div class="modal fade bd-example-modal-sm" id="passModal" role="dialog" aria-labelledby="modalPassLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalPassLabel">Cambiar contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formPass" onsubmit="event.preventDefault(); changePass(this);" action="" method="post">
                @csrf
                <div class="modal-body">
                    <div id="dato-cliente" class="mb-4"></div>
                    <label for="pass">Contraseña nueva</label>
                    <div class="input-group">
                        <input required type="text" id="pass" placeholder="Contraseña nueva" name="pass" class="form-control rounded-0 border-top-0 border-left-0 border-primary"/>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary rounded-0" type="button" onclick="mostrar(this);">Ocultar</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary">CAMBIAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="position-fixed w-100 h-100 bg-light d-none justify-content-center align-items-center" style="left: 0; top: 0; z-index: 1111" id="mascara">
    <div class="d-flex align-items-center">
        <div class="spinner-grow text-primary mr-2" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        ACTUALIZANDO
        <small class="badge badge-warning shake-constant shake-chunk text-uppercase ml-2">espere</small>
    </div>
</div>
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
            <div class="mt-3 alert alert-warning" role="alert">Si no desea cambiar la clave, deje el campo vacío.</div>
        </div>
        <div class="mt-2 d-flex">
            <div class="">
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn btn-info rounded-0"><i class="fas fa-key"></i></button>
                <span class="ml-2">Cambiar contraseña</span>
            </div>
            <div class="ml-4">
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button>
                <span class="ml-2">Editar cliente</span>
            </div>
            <div class="ml-4">
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button>
                <span class="ml-2">Eliminar cliente</span>
            </div>
        </div>
        <div class="card mt-2" id="wrapper-tabla">
            <div class="card-body"></div>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $data["clientes"]->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    window.pyrus = new Pyrus( "cliente" );
    window.elementos = @json( $data["clientes"] );
    mostrar = function( t ) {
        if( $( t ).closest( '.input-group' ).find( 'input' ).attr( 'type' ) == "text" ) {
            $( t ).text( "Ocultar" );
            $( t ).closest( '.input-group' ).find( 'input' ).attr( 'type' , 'password' );
        } else {
            $( t ).text( "Mostrar" );
            $( t ).closest( '.input-group' ).find( 'input' ).attr( 'type' , 'text' );
        }
    };

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
    localidades = function( t ) {
        let id = $( t ).val();
        $( t ).attr( "disabled" , true );
        axios.get(`{{ url('/adm/localidades/provincia/${id}') }}`, {
            responseType: 'json'
        })
        .then(function(res) {
            $( t ).removeAttr( "disabled" );
            if( parseInt( res.status ) == 200 ) {
                if( $( `#${window.pyrus.name}_localidad_id .add` ).length ) {
                    $( `#${window.pyrus.name}_localidad_id` ).attr( "disabled" , true );
                    $( `#${window.pyrus.name}_localidad_id .add` ).remove();
                }
                if( res.data.length > 0 ) {
                    res.data.forEach( function( l ) {
                        $( `#${window.pyrus.name}_localidad_id` ).append( `<option class="add" value="${l.id}">${l.nombre}</option>` );
                    } );
                    $( `#${window.pyrus.name}_localidad_id` ).removeAttr( "disabled" );
                    if( window.dataFull !== undefined ) {
                        $( `#${window.pyrus.name}_localidad_id` ).val( window.dataFull.localidad_id );
                        delete window.dataFull;
                    }
                }
            }
        })
        .catch(function(err) {})
        .then(function() {});
    };
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
    modelos = function( t, id ) {
        let url = `{{ url('/adm/marcas/${id}/modelos') }}`
        window.location = url;
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

    cambiarFunction = function( t , id ) {
        let index = $(t).closest( "tr" ).index();
        let h = "";

        h += `<h3 class="text-center">${window.elementos.data[ index ].username}</h3>`;
        h += `<input type="hidden" name="cliente_id" value="${window.elementos.data[ index ].id}"/>`;
        $( "#dato-cliente" ).html( h );
        $( "#passModal" ).modal( "show" );
    };
    changePass = function( t ) {
        let idForm = t.id;
        let url = t.action;
        let method = t.method;
        let formElement = document.getElementById(idForm);
        let formData = new FormData(formElement);

        alertify.warning("Espere. Guardando cambio");
        axios({
            method: method,
            url: url,
            data: formData,
            responseType: 'json',
            config: { headers: {'Content-Type': 'multipart/form-data' }}
        })
        .then(function(res) {
            if(parseInt(res.data) == 1) {
                alertify.success("Datos modificados.");
                $( "#pass" ).val( "" );
                $( "#passModal" ).modal( "hide" );
            } else
                alertify.error("Ocurrió un error en el guardado. Reintente");
        })
        .catch(function(err) {})
        .then(function() {});
    };
    /** ------------------------------------- */
    init = function( callbackOK ) {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $( "#form .container-form" ).html(window.pyrus.formulario());
        $( "#wrapper-tabla > div.card-body" ).html(window.pyrus.table([ {NAME:"ACCIONES", COLUMN: "acciones", CLASS: "text-center", WIDTH:"150px"} ]));
        
        
        window.pyrus.elements( $( "#tabla" ) , `{{ asset('/') }}` , window.elementos.data , [ "e" , "d" ] , [ { icon : '<i class="fas fa-key"></i>' , class: 'btn-info' , function : 'cambiar' } ] );
        window.pyrus.editor( CKEDITOR );
        callbackOK.call(this,null);
    }
    /** */
    init( function() {
        
    });
</script>
@endpush