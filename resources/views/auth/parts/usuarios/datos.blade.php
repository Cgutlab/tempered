<section class="mt-3">
    <div class="container">
        <div class="mt-3 row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Usuario: {{$data["usuario"]["username"]}}</h5>
                        <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/empresa/usuarios/update/' . $data['usuario']['id']) }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="usuarios_is_admin" value="{{ $data['usuario']['is_admin'] }}">
                            <input type="hidden" name="usuarios_username" value="{{ $data['usuario']['username'] }}">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" name="usuarios_name" id="name" placeholder="Nombre completo" value="{{$data['usuario']['name']}}">
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" name="usuarios_password" id="password" placeholder="Contraseña">
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-block btn-success text-center text-uppercase"></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="mt-3 alert alert-warning" role="alert">Si no desea cambiar la clave, deje el campo vacío.</div>
            </div>
        </div>
    </div>
</section>
@push( "scripts" )
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