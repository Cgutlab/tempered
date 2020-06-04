<section class="mt-3">
    <div class="container-fluid">
        <button id="btnADD" onclick="add(this)" class="btn position-fixed rounded-circle btn-primary text-uppercase" type="button"><i class="fas fa-plus"></i></button>
        <div style="display: none;" id="wrapper-form" class="mt-2">
            <div class="card">
                <div class="card-body">
                    <button onclick="remove(this)" type="button" class="close position-absolute" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/familia/store') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="container-form"></div>
                        <div></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-2" id="wrapper-tabla">
            <div class="card-body"></div>
        </div>
    </div>
</section>
@push('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    window.pyrus = new Pyrus("usuarios");
    window.usuarios = @json($data['usuarios']);

    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let method = t.method;
        let formElement = document.getElementById(idForm);
        if(method == "GET" || method == "get") method = "post";
        let formData = new FormData(formElement);

        for(let x in CKEDITOR.instances)
            formData.set(x,CKEDITOR.instances[`${x}`].getData());
        
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
    /** ------------------------------------- */
    readURL = function(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(`#${target}`).attr(`src`,`${e.target.result}`);
            };
            reader.readAsDataURL(input.files[0]);
        }
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
            $("#form .container-form + div").html('<div class="mt-3 row justify-content-center"><div class="col-12 col-md-6"><div class="alert alert-warning" role="alert">Si no desea cambiar la clave del usuario, deje el campo vacío.</div></div></div>');
            $("#password").removeAttr("required");
            $("#username").attr("readonly", true);
            method = "PUT";
            action = `{{ url('/adm/empresa/${window.pyrus.entidad}/update/${id}') }}`;
        } else {
            $("#form .container-form + div").html("");
            $("#password").attr("required", true);
            $("#username").removeAttr("readonly");
            method = "POST";
            action = `{{ url('/adm/empresa/${window.pyrus.entidad}/store') }}`;
        }
        if(data !== null) {
            for(let x in window.pyrus.especificacion) {
                $(`#${window.pyrus.name}_${x}`).val(data[x]).trigger("change");
            }
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
        $("#form").attr("method",method);
    };
    /** ------------------------------------- */
    erase = function(t, id) {
        $(t).attr("disabled",true);
        alertify.confirm("ATENCIÓN","¿Eliminar registro?",
            function(){
                let promise = new Promise(function (resolve, reject) {
                    let url = `{{ url('/adm/empresa/${window.pyrus.entidad}/delete/${id}') }}`;
                    var xmlHttp = new XMLHttpRequest();
                    xmlHttp.open( "GET", url, true );
                    
                    xmlHttp.send( null );
                    resolve(xmlHttp.responseText);
                });

                promiseFunction = () => {
                    promise
                        .then(function(msg) {
                            $("#tabla").find(`tr[data-id="${id}"]`).remove();
                        })
                };
                promiseFunction();
            },
            function() {
                $(t).removeAttr("disabled");
            }
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
    /** ------------------------------------- */
    remove = function(t) {
        add($("#btnADD"));

        for(let x in window.pyrus.especificacion)
            $(`#${window.pyrus.name}_${x}`).val("");
    };
    /** ------------------------------------- */
    edit = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            let url = `{{ url('/adm/empresa/${window.pyrus.entidad}/edit/${id}') }}`;
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.responseType = 'json';
            xmlHttp.open( "GET", url, true );
            xmlHttp.onload = function() {
                resolve(xmlHttp.response);
            }
            xmlHttp.send( null );
        });

        promiseFunction = () => {
            promise
                .then(function(data) {
                    console.log(data)
                    $(t).removeAttr("disabled");
                    add($("#btnADD"),parseInt(id),data);
                })
        };
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
    init = function() {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());
        $("#wrapper-tabla > div").html(window.pyrus.table([ {NAME:"ACCIONES", COLUMN: "acciones", CLASS: "text-center", WIDTH:"150px"} ]));
        let columnas = window.pyrus.columnas();
        let table = $("#tabla");
        
        window.usuarios.forEach(function(data) {
            let tr = "";
            if(!table.find("tbody").length) 
                table.append("<tbody></tbody>");
            columnas.forEach(function(c) {
                td = data[c.COLUMN] === null ? "" : data[c.COLUMN];
                if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${td}') }}?t=${date.getTime()}`;
                    td = `<img class="w-100" src="${img}" onerror="this.src='${src}'"/>`;
                }
                if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_ENUM") {
                    if(window.pyrus.especificacion[c.COLUMN].ENUM !== undefined) 
                        td = window.pyrus.especificacion[c.COLUMN].ENUM[td];
                }
                tr += `<td class="${c.CLASS}">${td}</td>`;
            });
            tr += `<td class="text-center"><button onclick="edit(this,${data.id})" class="btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button><button onclick="erase(this,${data.id})" class="btn btn-danger rounded-0"><i class="fas fa-trash-alt"></i></button></td>`;
            table.find("tbody").append(`<tr data-id="${data.id}">${tr}</tr>`);
        });
    }
    /** */
    init();
</script>
@endpush