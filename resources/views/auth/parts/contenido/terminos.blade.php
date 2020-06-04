<div id="wrapper-form" class="mt-2">
    <div class="card">
        <div class="card-body">
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/contenido/' . $data['section'] . '/update') }}" method="put" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-success text-uppercase px-5 mx-auto d-flex align-items-center mb-5">contenido<i class="fas fa-pencil-alt ml-2"></i></button>
                <div class="container-form"></div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script>
    window.pyrus = new Pyrus("terminos", null, src);
    window.contenido = @json($data["contenido"]);

    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let promise = new Promise(function (resolve, reject) {
            let formElement = document.getElementById(idForm);
            let request = new XMLHttpRequest();
            let formData = new FormData(formElement);
            formData.append("ATRIBUTOS",JSON.stringify(
                [
                    { DATA: window.pyrus.objetoSimple, TIPO: "U" }
                ]
            ));

            for(let x in CKEDITOR.instances)
                formData.set(x,CKEDITOR.instances[`${x}`].getData());
                
            request.responseType = 'json';
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "POST", url );
            xmlHttp.onload = function() {
                resolve(xmlHttp.response);
            }
            xmlHttp.send( formData );
        });
        promiseFunction = () => {
            promise
                .then(function(data) {
                    if(parseInt(data) == 1) {
                        alertify.success("Contenido guardado");
                        location.reload();
                    } else 
                        alertify.error("Ocurrió un error en el guardado. Reintente");
                }
        )};
        alertify.warning("Espere. Guardando contenido");
        promiseFunction();
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
    /** ------------------------------------- */
    init = function(callbackOK) {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());
        CKEDITOR.replace( `${window.pyrus.name}_texto` , {
            toolbarGroups: [
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: "basicstyles",groups: ["basicstyles"] },
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'links' },
                { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
            ],
            removeButtons: 'Save,NewPage,Print,Preview,Templates'
        } );
        setTimeout(() => {
            callbackOK.call(this);
        }, 50);
    }
    /** */
    init(function() {
        for(let x in window.pyrus.especificacion) {
            $(`#${window.pyrus.name}_${x}`).val(window.contenido.data[x]);
        }
        //$(`[name="titulo_es"]`).val(window.contenido.data.CONTENIDO.titulo.es);
    });
</script>
@endpush