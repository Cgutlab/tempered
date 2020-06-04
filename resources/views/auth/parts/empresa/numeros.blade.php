<h3 class="title">{{$title}}</h3>

<section class="mt-3">
    <div class="container-fluid">
        <div>
            <button id="btnADD" onclick="add(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>
        </div>
        <div style="display: none;" id="wrapper-form" class="mt-2">
            <div class="card">
                <div class="card-body">
                    <button onclick="remove(this)" type="button" class="close position-absolute" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <form id="form" novalidate class="pt-2" action="{{ url('/adm/familia/store') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="container-form"></div>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6"><hr></div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6">
                                <button id="btnEmail" type="button" class="btn btn-block btn-info text-center text-uppercase" onclick="addEmail(this)">Email<i class="fas fa-plus ml-2"></i></button>
                                <div class="container-form-email row" id="wrapper-email"></div>
                            </div>
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
    </div>
</section>
@push('scripts_distribuidor')
<script>

    window.pyrus = new Pyrus("numeros", null);
    window.pyrusEmail = new Pyrus("empresa_email");
    window.elementos = @json($numeros);
    
    /** ------------------------------------- */
    add = function(t, id = 0, data = null) {
        let btn = $(t);
        if(btn.is(":disabled"))
            btn.removeAttr("disabled");
        else
            btn.attr("disabled",true);
        $("#wrapper-form").toggle(800,"swing");

        $("#wrapper-tabla").toggle("fast");

        if(id != 0)
            action = `{{ url('/adm/empresa/${window.pyrus.entidad}/update/${id}') }}`;
        else
            action = `{{ url('/adm/empresa/${window.pyrus.entidad}/store') }}`;
        if(data !== null) {
            for(let x in window.pyrus.especificacion) {
                if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                    CKEDITOR.instances[`${x}_es`].setData(data[x]);
                    continue;
                }
                if(window.pyrus.especificacion[x].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${data[x]}') }}?t=${date.getTime()}`;
                    $(`#src-${x}`).attr("src",img);
                    continue;
                }
                $(`[name="${x}"]`).val(data[x]);
            }

            data.email = JSON.parse(data.email);
            data.email.forEach(function(e) {
                addEmail($("#btnEmail"), e);
            });
        } else {
            if($("#tabla tbody").length)
                $("#orden").val($("#tabla tbody").find("tr:last-child() td[data-orden]").text());
            else
                $("#orden").val("AA");
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
    };
    /** ------------------------------------- */
    addEmail = function(t, value = null) {
        let target = $(`#wrapper-email`);
        let html = "";
        if(window.email === undefined) window.email = 0;
        window.email ++;

        html += '<div class="col-12 mt-2 tel position-relative">';
            html += '<div class="bg-light p-2 border">';
                html += window.pyrusEmail.formulario(window.email,"email");
                html += `<i style="line-height:14px; cursor: pointer; right: 5px; top: -10px; padding: 5px;" onclick="$(this).closest('.tel').remove()" class="fas fa-times position-absolute text-white bg-danger rounded-circle"></i>`;
            html += '</div>';
        html += '</div>';
    
        target.append(html);
        if(value !== null)
            target.find("> div:last-child input").val(value);
    }
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
        $(`#wrapper-email`).html("");

        for(let x in window.pyrus.especificacion) {
            if(window.pyrus.especificacion[x].EDITOR !== undefined) {
                CKEDITOR.instances[x].setData('');
                continue;
            }
            if(window.pyrus.especificacion[x].TIPO == "TP_FILE")
                $(`#src-${x}`).attr("src","");
            $(`[name="${x}"]`).val("");
        }
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
    /** ------------------------------------- */
    init = function() {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());

        let columnas = window.pyrus.columnas();console.log(columnas)
        let table = $("#tabla");
        columnas.forEach(function(e) {
            if(!table.find("thead").length) 
                table.append('<thead class="thead-dark"></thead>');
            table.find("thead").append(`<th class="${e.CLASS}" style="width:${e.WIDTH}">${e.NAME}</th>`);
        });
        table.find("thead").append(`<th class="text-uppercase text-center" style="width:150px">acción</th>`);

        window.elementos.forEach(function(data) {
            let tr = "";
            if(!table.find("tbody").length) 
                table.append("<tbody></tbody>");
            columnas.forEach(function(c) {
                td = data[c.COLUMN] === null ? "" : data[c.COLUMN];
                if(c.COLUMN == "documento") {
                    file = `{{ asset('${td}') }}`;
                    td = `<a class="text-uppercase text-primary" href="${file}" target="blank">documento<i class="fas fa-external-link-alt ml-1"></i></a>`;
                } else if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${td}') }}?t=${date.getTime()}`;
                    td = `<img class="w-100" src="${img}" onerror="this.src='${src}'"/>`;
                } else if(window.pyrus.especificacion[c.COLUMN].TIPO == "TP_JSON") {
                    aux = JSON.parse(td);
                    td = "";
                    for(x in aux)
                        td += `<p class="mb-0">${aux[x]}</p>`;
                }
                tr += `<td data-${c.COLUMN} class="${c.CLASS}">${td}</td>`;
            });
            tr += `<td class="text-center"><button onclick="edit(this,${data.id})" class="btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></button><button onclick="erase(this,${data.id})" class="btn rounded-0 btn-danger"><i class="fas fa-trash-alt"></i></button></td>`;
            table.find("tbody").append(`<tr data-id="${data.id}">${tr}</tr>`);
        });
    }
    /** */
    init();
</script>
@endpush