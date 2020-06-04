<button id="btnADD" onclick="add(this)" class="position-fixed btn btn-primary rounded-circle text-uppercase" type="button"><i class="fas fa-plus"></i></button>
<div style="display: none;" id="wrapper-form" class="mt-2">
    <div class="card">
        <div class="card-body">
            <button onclick="remove(this)" type="button" class="close position-absolute" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times"></i></span>
            </button>
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/familia/store') }}" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="container-form"></div>
                <div class="alert mt-5 mb-0 alert-primary" role="alert">
                    Los links de las redes sociales deberán tener la siguiente estructura: <span class="text-primary"><strong>https://</strong>www.links.com</span> o <span class="text-primary"><strong>http://</strong>www.links.com</span>
                </div>
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

@push('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    window.pyrus = new Pyrus("redes");
    window.elementos = @json($data["contenido"]);
    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let method = t.method;
        let formElement = document.getElementById(idForm);
        if(method == "GET" || method == "get") method = "post";
        let formData = new FormData(formElement);
        formData.append("ATRIBUTOS",JSON.stringify(
            [
                { DATA: window.pyrus.objetoSimple, TIPO: "U", PALABRA: "redes" }
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
            action = `{{ url('/adm/empresa/${window.pyrus.entidad}/update/${id}') }}`;
            method = "PUT";
        } else {
            action = `{{ url('/adm/empresa/${window.pyrus.entidad}/store') }}`;
            method = "POST";
        }
        if(data !== null) {
            for(let x in window.pyrus.especificacion) {
                $(`#${window.pyrus.name}_${x}`).val(data[x]);
            }
        } else {
            if($("#tabla tbody").length)
                $("#orden").val($("#tabla tbody").find("tr:last-child() td[data-orden]").text());
            else
                $("#orden").val("AA");
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
        $("#form").attr("method",method);
    };
    /** ------------------------------------- */
    remove = function(t) {
        add($("#btnADD"));

        for(let x in window.pyrus.especificacion) {
            if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                CKEDITOR.instances[x].setData('');
                continue;
            }
            if(window.pyrus.especificacion[x].TIPO == "TP_IMAGE")
                $(`#src-${x}`).attr("src","");
            $(`[name="${x}"]`).val("");
        }
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
    edit = function(t, id) {
        $(t).attr("disabled",true);
        let promise = new Promise(function (resolve, reject) {
            resolve(window.elementos[id]);
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
        let ARR_redes = {facebook:'<i class="fab fa-facebook-square"></i>',instagram:'<i class="fab fa-instagram"></i>',twitter:'<i class="fab fa-twitter-square"></i>',youtube:'<i class="fab fa-youtube"></i>',linkedin:'<i class="fab fa-linkedin-in"></i>'};

        for(let x in window.elementos) {
            let tr = "";
            let data = window.elementos[x];
            if(!table.find("tbody").length) 
                table.append("<tbody></tbody>");
            columnas.forEach(function(c) {
                td = data[c.COLUMN] === null ? "" : data[c.COLUMN];
                if(c.COLUMN == "redes")
                    td = `${ARR_redes[data[c.COLUMN]]} ${data[c.COLUMN]}`;
                
                if(c.COLUMN == "url")
                    td = `<a href="${td}" class="text-primary">LINK <i class="fas fa-external-link-alt"></i></a>`;
                
                tr += `<td data-${c.COLUMN} class="${c.CLASS}">${td}</td>`;
            });
            tr += `<td class="text-center"><button onclick="edit(this,${x})" class="btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button><button onclick="erase(this,${x})" class="btn rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button></td>`;
            table.find("tbody").append(`<tr data-id="${x}">${tr}</tr>`);
        }
    }
    /** */
    init();
</script>
@endpush