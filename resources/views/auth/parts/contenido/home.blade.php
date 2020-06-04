<div class="alert alert-info" role="alert">
    Textos, imágenes o íconos pertenecientes a la sección. <a class="text-primary" target="blank" href="{{ route('index') }}">Ver vista pública de la sección <i class="fas fa-external-link-alt"></i></a>
</div>
<div class="wrapper-home mt-2">
    <div class="card rounded-0">
        <div class="card-body iconos py-5">
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
<div id="wrapper-form" class="mt-2">
    <div class="card bg-light">
        <div class="card-body">
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/contenido/' . $data['section'] . '/update') }}" method="put" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-success text-uppercase px-5 mx-auto d-flex align-items-center mb-5">contenido<i class="fas fa-pencil-alt ml-2"></i></button>
                <div class="card bg-white shadow mt-5">
                    <div class="card-body">
                        <button onclick="addIcono(this);" type="button" class="btn btn-dark text-uppercase px-5 mx-auto d-flex align-items-center mb-2">ícono <small class="ml-2">(Ícono + Texto)</small><i class="fas fa-plus ml-2"></i></button>
                        <div id="targetIconos" class="target">
                            <div class="row d-block" id="targetIconosRow"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script src="http://SortableJS.github.io/Sortable/Sortable.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    window.pyrus_icono = new Pyrus("home_icono", null, src);
    window.contenido = @json($data["contenido"]);

    formSubmit = function(t) {
        let idForm = t.id;
        let formElement = document.getElementById( idForm );

        let formData = new FormData( formElement );
        formData.append( "ATRIBUTOS", JSON.stringify(
            [
                { DATA: window.pyrus_icono.objetoSimple, TIPO: "M", KEY: "iconos" , COLUMN: "iconos" , TAG : "iconos" ,BUCLE:`${window.pyrus_icono.name}_removeIcono` }
            ]
        ));
        for( let x in CKEDITOR.instances )
            formData.set( x , CKEDITOR.instances[ `${x}` ].getData() );
        formSave( t , formData );
    };
    removeIcono = function(t) {
        if($(t).closest(".icono").find(".imageURL_hidden").val() == "") {
            $(t).closest(".icono").remove();
            return null;
        }
        $(t).attr("disabled",true);
        alertify.confirm("ATENCIÓN","¿Esta seguro de eliminar ícono?",
            function() {
                $(t).closest(".icono").addClass("d-none");
                $(t).closest(".icono").find(".imageRemove").val(1);
            },
            function() {
                $(t).removeAttr("disabled");
            }
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
    addIcono = function(t, value = null) {
        let target = $(`#targetIconos`);
        let html = "";

        if(window.countIcono === undefined) window.countIcono = 0;
        window.countIcono ++;

        html += '<div class="col-12 icono position-relative">';
            html += `<i class="fas fa-arrows-alt p-2 bg-warning position-absolute move" style="top: 0; right: 27px; z-index: 11; cursor: pointer;"></i>`;
            html += `<div class="p-2">`;
                html += window.pyrus_icono.formulario(window.countIcono,"iconos");
                html += `<input type="hidden" class="imageRemove" name="${window.pyrus_icono.name}_removeIcono[]" value="0"/>`;
                html += `<input type="hidden" class="imageURL_hidden" name="${window.pyrus_icono.name}_imageURL[]" value="" />`;
                html += `<i onclick="removeIcono(this);" class="fas fa-times position-absolute p-2 bg-danger text-white" style="top: 0; right: 0; z-index: 11; cursor: pointer;"></i>`;
            html += '</div>';
        html += '</div>';
        $("#targetIconos > div").append(html);
        
        window.pyrus_icono.show( CKEDITOR , `{{ asset('/') }}` , value , window.countIcono , "iconos" );
        window.pyrus_icono.editor( CKEDITOR , window.countIcono , "iconos" );
    };
    removeData = function(t) {
        if($(t).closest(".numero").find(".imageURL_hidden").val() == "") {
            $(t).closest(".numero").remove();
            return false;
        }
        alertify.confirm("ATENCIÓN","¿Eliminar elemento?",
            function() {
                $(t).closest(".numero").find(".imageRemove").val("1");
                $(t).closest(".numero").addClass("d-none");
            },
            function() {
                $(t).removeAttr("disabled");
            }
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
    Sortable.create(targetIconosRow, {
        handle: ".move",
        animation: 150,
        ghostClass: 'blue-background-class',
        onStart: function (evt) {
            let h = CKEDITOR.instances[$(evt.item).find("textarea").attr("id")].getData()
            $(evt.item).find("textarea").addClass("d-none");
            $(evt.item).find("textarea").parent().append(`<div class="append-html w-100 p-5 bg-light text-truncate border"></div>`);
            $(evt.item).find(".append-html").text(h);
            CKEDITOR.instances[$(evt.item).find("textarea").attr("id")].destroy()
        },
        onEnd: function (evt) {
            $(evt.item).find("textarea").removeClass("d-none");
            $(evt.item).find(".append-html").remove();
            CKEDITOR.replace($(evt.item).find("textarea").attr("id"), {
                toolbarGroups: [{
                        "name": "basicstyles",
                        "groups": ["basicstyles"]
                    },
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ],
            });
        },
    });
    shortcut.add("Alt+Ctrl+S", function () {
        if($("#form").is(":visible")) {
            $("#form").submit();
        }
    }, {
        "type": "keydown",
        "propagate": true,
        "target": document
    });
    /** ------------------------------------- */
    init = function(callbackOK) {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        let html = "";
        $("#form .container-form").html(html);
        
        setTimeout(() => {
            callbackOK.call(this);
        }, 50);
    }
    /** */
    init(function() {
        window.contenido.data.iconos.forEach( function( i ) {
            addIcono( null , i);
        });
    });
</script>
@endpush