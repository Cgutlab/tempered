<div class="d-none">
    <button disabled="true" id="btnADD" onclick="add(this)" class="btn btn-primary text-uppercase" type="button">Agregar<i class="fas fa-plus ml-2"></i></button>
</div>
<div style="display: none;" id="wrapper-form" class="">
    <div class="card">
        <div class="card-body">
            <button onclick="remove(this)" type="button" class="close position-absolute" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times"></i></span>
            </button>
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/familia/store') }}" method="put" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button class="btn btn-success px-5 text-uppercase d-block mx-auto mb-4"><i class="fas fa-save ml-3"></i></button>
                <div class="container-form"></div>
            </form>
        </div>
    </div>
</div>
<div class="card mt-2" id="wrapper-tabla">
    <div class="card-body">
        <table class="table mb-0" id="tabla"></table>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    window.pyrus = new Pyrus("metadatos");
    window.metadatos = @json($data["contenido"]["metadatos"]);
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
    add = function(t, seccion = "", data = null) {
        let btn = $(t);

        $("#wrapper-form").toggle(800,"swing");

        $("#wrapper-tabla").toggle("fast");

        if(seccion != "") {
            method = "put";
            action = `{{ url('/adm/empresa/${window.pyrus.name}/update/${seccion}') }}`;
        } else {
            method = "post";
            action = `{{ url('/adm/empresa/${window.pyrus.name}/' . strtolower($data["seccion"]) . '/store') }}`;
        }
        $("#form button").text(`${seccion.toUpperCase()}`);
        if(data !== null) {
            $(`#${window.pyrus.name}_seccion`).val(seccion);
            for(let x in window.pyrus.especificacion) {
                if(x == "seccion") continue;
                
                $(`#${window.pyrus.name}_${x}`).val(data[x]);
            }
        }
        elmnt = document.getElementById("form");
        elmnt.scrollIntoView();
        $("#form").attr("action",action);
        $("#form").attr("method",method);
        //$("#secc").html("<strong>SECCIÓN:</strong> " + seccion.toUpperCase());
    };
    /** ------------------------------------- */
    remove = function(t) {
        add($("#btnADD"));

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
    edit = function(t, seccion) {
        //$(t).attr("disabled",true);
        add($("#btnADD"),seccion,window.metadatos[seccion]);
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
    init = function() {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        $("#form .container-form").html(window.pyrus.formulario());

        let columnas = window.pyrus.columnas();
        let table = $("#tabla");
        columnas.forEach(function(e) {
            if(!table.find("thead").length) 
                table.append("<thead class='thead-dark'></thead>");
            table.find("thead").append(`<th class="${e.CLASS}" style="width:${e.WIDTH}">${e.NAME}</th>`);
        });
        table.find("thead").append(`<th class="text-uppercase text-center" style="width:150px">acción</th>`);

        for(let x in window.metadatos) {
            let tr = "";
            if(!table.find("tbody").length) 
                table.append("<tbody></tbody>");
            tr += `<td class="text-uppercase text-left">${x}</td>`;
            tr += `<td>${window.metadatos[x].metas === null ? "-" : window.metadatos[x].metas}</td>`;
            tr += `<td>${window.metadatos[x].descripcion === null ? "-" : window.metadatos[x].descripcion}</td>`;
            tr += `<td class="text-center"><button onclick="edit(this,'${x}')" class="btn rounded-0 btn-warning"><i class="fas fa-pencil-alt"></i></td>`;
            table.find("tbody").append(`<tr data-id="${x}">${tr}</tr>`);
        }
    }
    /** */
    init();
</script>
@endpush