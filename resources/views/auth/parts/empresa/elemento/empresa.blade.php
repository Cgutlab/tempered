<div style="display: block;" id="wrapper-form" class="">
    <div class="card">
        <div class="card-body">
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/empresa/update') }}" method="put" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn d-block px-5 mx-auto mb-2 btn-success text-uppercase text-center">cambios<i class="far fa-save ml-2"></i></button>
                <div class="container-form">
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    window.pyrusImage = new Pyrus("empresa_images", null, src);
    window.pyrusDomicilio = new Pyrus("empresa_domicilio");
    window.pyrusTelefono = new Pyrus("empresa_telefono");
    window.pyrusEmail = new Pyrus("empresa_email");
    
    window.datos = @JSON($data["contenido"])
    
    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let method = t.method;
        let formElement = document.getElementById(idForm);
        if(method == "GET" || method == "get") method = "post";
        let formData = new FormData(formElement);
        formData.append("ATRIBUTOS",JSON.stringify(
            [
                { DATA: window.pyrusImage.objetoSimple, TIPO: "U", PALABRA: "images" },
                { DATA: window.pyrusDomicilio.objetoSimple, TIPO: "U", PALABRA: "domicilio" },
                { DATA: window.pyrusTelefono.objetoSimple, TIPO: "M", PALABRA: "telefono", BUCLE: `${window.pyrusTelefono.name}_telefono_tipo` },
                { DATA: window.pyrusEmail.objetoSimple, TIPO: "M", PALABRA: "email" }
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
    addTelefono = function(t, value = null) {
        let target = $(`#wrapper-telefono`);
        let html = "";
        if(window.telefono === undefined) window.telefono = 0;
        window.telefono ++;

        html += '<div class="col-12 col-md-6 my-2 tel">';
            html += '<div class="bg-light p-2 border overflow-hidden position-relative">';
                html += window.pyrusTelefono.formulario(window.telefono,"telefono");
                html += `<i style="line-height:14px; cursor: pointer; right: 0; top: 0; padding: 5px;border-radius: 0 0 0 .4em;" onclick="$(this).closest('.tel').remove()" class="fas fa-times position-absolute text-white bg-danger"></i>`;
            html += '</div>';
        html += '</div>';
    
        target.append(html);

        if(value !== null) {
            for(let x in window.pyrusTelefono.especificacion) {
                if( window.pyrusTelefono.especificacion[x].TIPO == "TP_CHECK" ) {
                    if( value[ x ] !== null ) {
                        $(`#${window.pyrusTelefono.name}_telefono_${x}_${window.telefono}`).prop( "checked",true );
                        $(`#${window.pyrusTelefono.name}_telefono_${x}_${window.telefono}_input`).val( 1 );
                    }
                } else
                    $(`#${window.pyrusTelefono.name}_telefono_${x}_${window.telefono}`).val( value[ x ] ).trigger( "change" );
            }
        }
    }
    
    addEmail = function(t, value = null) {
        let target = $(`#wrapper-email`);
        let html = "";
        if(window.email === undefined) window.email = 0;
        window.email ++;

        html += '<div class="col-4 my-2 tel">';
            html += '<div class="bg-light p-2 border position-relative overflow-hidden">';
                html += window.pyrusEmail.formulario(window.email,"email");
                html += `<i style="line-height:14px; cursor: pointer; right: 0; top: 0; padding: 5px;border-radius: 0 0 0 .4em;" onclick="$(this).closest('.tel').remove()" class="fas fa-times position-absolute text-white bg-danger"></i>`;
            html += '</div>';
        html += '</div>';
    
        target.append(html);
        if(value !== null)
            target.find("> div:last-child input").val(value);
    }
    /** ------------------------------------- */
    init = function(callbackOK) {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        form = "";
        /** */
        form += `<fieldset class="border p-3">`;
            form += `<legend class="border-bottom">Logotipos & Favicon</legend>`;
            form += window.pyrusImage.formulario();
        form += `</fieldset>`;
        form += `<fieldset class="border p-3">`;
            form += `<legend class="border-bottom">Domicilio</legend>`;
            form += window.pyrusDomicilio.formulario();
        form += `</fieldset>`;
        form += '<div class="row justify-content-center pt-3 border-top">';
            form += '<div class="col-md-3 col-12">';
                form += '<button id="btnTelefono" type="button" class="btn btn-block btn-dark text-center text-uppercase" onclick="addTelefono(this)">Teléfono<i class="fas fa-plus ml-2"></i></button>';
            form += `</div>`;
        form += `</div>`;
        form += '<div class="row mt-0" id="wrapper-telefono"></div>';
        
        form += '<div class="row justify-content-center pt-3 border-top">';
            form += '<div class="col-md-3 col-12">';
                form += '<button id="btnEmail" type="button" class="btn btn-block btn-info text-center text-uppercase" onclick="addEmail(this)">Email<i class="fas fa-plus ml-2"></i></button>';
            form += `</div>`;
        form += `</div>`;
        form += '<div class="row mt-0" id="wrapper-email"></div>';

        $("#form .container-form").html(form);
        setTimeout(() => {
            callbackOK.call(this);
        }, 50);
    }
    shortcut.add("Alt+Ctrl+S", function () {
        if($("#form").is(":visible")) {
            $("#form").submit();
        }
    }, {
        "type": "keydown",
        "propagate": true,
        "target": document
    });
    /** */
    init(function() {
        date = new Date();
        logo = logoFooter = favicon = "";
        if(window.datos.images.logo !== undefined)
            logo = `{{ asset('${window.datos.images.logo.i}') }}?t=${date.getTime()}`;
        if(window.datos.images.logoFooter !== undefined)
            logoFooter = `{{ asset('${window.datos.images.logoFooter.i}') }}?t=${date.getTime()}`;
        if(window.datos.images.favicon !== null)
            favicon = `{{ asset('${window.datos.images.favicon.i}') }}?t=${date.getTime()}`;
        $(`#src-${window.pyrusImage.name}_logo`).attr("src",logo);
        $(`#src-${window.pyrusImage.name}_logoFooter`).attr("src",logoFooter);
        $(`#src-${window.pyrusImage.name}_favicon`).attr("src",favicon);

        if( Object.keys(window.datos.domicilio.calle).length > 0) {
            for(let x in window.pyrusDomicilio.especificacion) 
                $(`#${window.pyrusDomicilio.name}_${x}`).val(window.datos.domicilio[x]);
        }
        window.datos.email.forEach(function(e) {
            addEmail($("#btnEmail"), e);
        });

        window.datos.telefono.forEach(function(t) {
            addTelefono($("#btnTelefono"),t);
        });
    });
</script>
@endpush