<div style="display: block;" id="wrapper-form" class="">
    <div class="card">
        <div class="card-body">
            <div class="alert alert-primary" role="alert">
                Ingrese a qué mail desea dirigir los formularios que contiene el sitio.
            </div>
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" class="pt-2" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn d-block px-5 mx-auto mb-2 btn-success text-uppercase text-center">cambios<i class="far fa-save ml-2"></i></button>
                <div class="container-form mt-5">
                    <div class="row mt-3">
                        <div class="col-12 col-md-5 d-flex align-items-center">
                            <label for="" class="m-0">
                                Contacto - <a href="{{ route('contacto') }}" target="blank" class="text-primary">ir al Formulario</a>
                            </label>
                        </div>
                        <div class="col-12 col-md-7">
                            <input type="email" required name="contacto" placeholder="Ingrese mail" value="{{ $data['contenido']['form']['contacto'] }}" class="form-control border-top-0 border-left-0 border-right-0 rounded-0">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-md-5 d-flex align-items-center">
                            <label for="" class="m-0">
                                Solicitar presupuesto - <a href="{{ route('presupuesto') }}" target="blank" class="text-primary">ir al Formulario</a>
                            </label>
                        </div>
                        <div class="col-12 col-md-7">
                            <input type="email" required name="presupuesto" placeholder="Ingrese mail" value="{{ $data['contenido']['form']['presupuesto'] }}" class="form-control border-top-0 border-left-0 border-right-0 rounded-0">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let method = t.method;
        let formElement = document.getElementById(idForm);
        if(method == "GET" || method == "get") method = "post";
        let formData = new FormData(formElement);

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
    shortcut.add("Alt+Ctrl+S", function () {
        if($("#form").is(":visible")) {
            $("#form").submit();
        }
    }, {
        "type": "keydown",
        "propagate": true,
        "target": document
    });
</script>
@endpush