<section class="mt-3">
    <div class="container-fluid">
        <button id="btnADD" onclick="add(this)" class="btn position-fixed rounded-circle btn-primary text-uppercase" type="button"><i class="fas fa-plus"></i></button>
        
        <div class="mt-2 wrapper-familia font-opensans">
            <div class="card rounded-0">
                <div class="card-body rounded-0">
                    <div class="row">
                        @foreach( $data[ "productos" ] AS $f )
                        <div class="col-12 col-md-6 col-lg-4 my-3">
                            <a href="{{ URL::to('/adm/producto/' . $f[ 'url' ] . '/' . $f[ 'id' ]) }}" class="border bg-white d-block familia">
                                @php
                                $image = "";
                                $images = $f->images;
                                if( count( $images ) > 0 )
                                    $image = $images[0][ "image" ][ 'i' ];
                                @endphp
                                <img src="{{ asset( $image ) }}" class="w-100 d-block" alt="" srcset="">
                                <p class="title p-4 text-center">{{ $f[ "name" ] }}</p>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-2 d-flex">
            <div>
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn btn-info rounded-0" disabled><i class="far fa-clone"></i></button>
                <span class="ml-2"><strike>Copiar elemento</strike></span>
            </div>
            <div class="ml-4">
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn btn-primary rounded-0"><i class="fas fa-images"></i></button>
                <span class="ml-2">Agregar imágenes al producto</span>
            </div>
            <div class="ml-4">
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button>
                <span class="ml-2">Editar producto</span>
            </div>
            <div class="ml-4">
                <button style="width: 34px; text-align: center" type="button" class="btn-sm btn rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button>
                <span class="ml-2">Eliminar producto</span>
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
    window.pyrus = new Pyrus( "producto" , null , src );
    window.familia = @json( $data[ "familia" ] );
    window.categoria = @json( $data[ "categoria" ] );
    window.elementos = @json( $data[ "productos" ] );

    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let method = t.method;
        let formElement = document.getElementById(idForm);
        if(method == "GET" || method == "get") method = "post";
        let formData = new FormData(formElement);
        
        for(let x in CKEDITOR.instances)
            formData.append(x,CKEDITOR.instances[`${x}`].getData());
        formData.append("ATRIBUTOS",JSON.stringify(
            [
                { DATA: window.pyrus.objetoSimple, TIPO: "U" }
            ]
        ));

        alertify.warning("Espere. Guardando contenido");
        axios({
            method: method,
            url: url,
            data: formData,
            responseType: 'json',
            config: { headers: {'Content-Type': 'multipart/form-data' }}
        })
        .then(function(res) {
            if(parseInt(res.data) == 1) {
                alertify.success("Contenido guardado");
                location.reload();
            } else 
                alertify.error("Ocurrió un error en el guardado. Reintente");
        })
        .catch(function(err) {})
        .then(function() {});
    };
    removeFile = function(t) {
        $( t ).prop( "disabled" , false );
        doc = `{{ asset('${window.dataFULL.file}') }}`;
        alertify.confirm("ATENCIÓN",`<p>¿Eliminar documento subido?</p><p class="mb-0"><a href="${doc}" target="blank" class="text-primary">Ver ARCHIVO <i class="fas fa-external-link-alt"></i></a></p>`,
            function() {
                axios.get(`{{ url('/adm/${window.pyrus.name}/deleteDoc/${window.dataFULL.id}') }}`, {
                    responseType: 'json'
                })
                .then(function(res) {
                    $( t ).prop( "disabled" , true );
                    $(`#${window.pyrus.name}_file_button`).prop( "disabled" , true );
                    alertify.success( "Archivo eliminado" );
                })
                .catch(function(err) {})
                .then(function() {});
            },
            function() {
                $(t).removeAttr("disabled");
            }
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
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
        $("#form").attr("action",action);
        $("#form").attr("method",method);
        if(data !== null) {
            window.dataFULL = data;
            for(let x in window.pyrus.especificacion) {
                if(window.pyrus.especificacion[x].TIPO == "TP_FILE") {
                    if( data[x] !== null || data[x] != "" ) {
                        $(`#${window.pyrus.name}_${x}_button`).prop( "disabled" , false );
                    } 
                    continue;
                }
                if(window.pyrus.especificacion[x].TIPO == "TP_IMAGE") {
                    date = new Date();
                    img = `{{ asset('${data[x]}') }}?t=${date.getTime()}`;
                    $(`#src-${window.pyrus.name}_${x}`).attr("src",img);
                    continue;
                }
                
                if(CKEDITOR.instances[`${window.pyrus.name}_${x}`] !== undefined) {
                    CKEDITOR.instances[`${window.pyrus.name}_${x}`].setData(data[x]);
                }else
                    $(`#${window.pyrus.name}_${x}`).val(data[x]);
            }
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
    };
    /** ------------------------------------- */
    erase = function(t, id) {
        $(t).attr("disabled",true);
        alertify.confirm("ATENCIÓN","¿Eliminar registro?",
            function(){
                axios.delete(
                    `{{ url('/adm/${window.pyrus.name}/delete') }}`,
                    {params: {id: id}})
                    .then(function(res) {
                        if(parseInt(res.data) == 1) {
                            alertify.success("Contenido eliminado");
                            location.reload();
                        } else 
                            alertify.error("Ocurrió un error al eliminar. Reintente");
                    }).catch(function(err) {
                        console.log(err)
                    }).then(function() {});
            },
            function() {
                $(t).removeAttr("disabled");
            }
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
    /** ------------------------------------- */
    remove = function(t) {
        if(window.close !== undefined) {
            alertify.confirm("ATENCIÓN","¿Cerrar sin guardar registro?",
                function() {
                    add($("#btnADD"));

                    for(let x in window.pyrus.especificacion) {
                        if(CKEDITOR.instances[`${window.pyrus.name}_${x}`] !== undefined) {
                            CKEDITOR.instances[`${window.pyrus.name}_${x}`].setData("");
                            continue;
                        }
                        if(window.pyrus.especificacion[x].TIPO == "TP_IMAGE")
                            $(`#src-${window.pyrus.name}_${x}`).attr("src","");
                        $(`#${window.pyrus.name}_${x}`).val("");
                    }
                },
                function() {}
            ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
        }
    };
    /** ------------------------------------- */
    edit = function(t, id) {
        $(t).attr("disabled",true);
        axios.get(`{{ url('/adm/${window.pyrus.name}/${id}/edit') }}`, {
            responseType: 'json'
        })
        .then(function(res) {
            $(t).removeAttr("disabled");
            add($("#btnADD"),parseInt(id),res.data);
        })
        .catch(function(err) {})
        .then(function() {});
    };
    clone = function( t, id ) {

    };
    imagesFunction = function( t , id ) {
        let index = $( t ).closest( "tr" ).index();
        let producto = window.elementos[ index ];
        console.log(producto)
        let url = `{{ url('/adm/producto/${producto.url}/${id}') }}`;
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
    /** ------------------------------------- */
    init = function( callbackOK ) {
        $( "#form .container-form" ).html(window.pyrus.formulario());
        $( "#wrapper-tabla > div.card-body" ).html(window.pyrus.table([ {NAME:"ACCIONES", COLUMN: "acciones", CLASS: "text-center", WIDTH:"150px"} ]));
        window.pyrus.elements( $( "#tabla" ) , `{{ asset('/') }}` , window.elementos , [ "e" , "d" ] , [ { icon : '<i class="fas fa-images"></i>' , class: 'btn-primary' , function : 'images' } ] );
        window.pyrus.editor( CKEDITOR );
        
        callbackOK.call(this,null);
    }
    /** */
    init( function() {
        
    });
</script>
@endpush