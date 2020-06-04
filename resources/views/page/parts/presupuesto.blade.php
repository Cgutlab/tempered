<div class="wrapper-presupuesto py-4">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10">
                <div class="alert alert-primary d-none" id="notificacion"></div>
                <form novalidate onsubmit="event.preventDefault(); enviar(this)" id="form" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content-md-center">
                        <div class="col-md-8 col-12 d-flex justify-content-center align-items-center">
                            <div>
                                <span class="img-1"></span>
                                <p class="text-uppercase">tus datos</p>
                            </div>
                            <span class="linea w-50 mx-4"></span>
                            <div>
                                <span class="img-2 inactivo"></span>
                                <p class="text-uppercase">tu consulta</p>
                            </div>
                        </div>
                    </div>
                    <div id="primero" class="mt-5">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <input type="text" name="nombre" id="nombre" placeholder="Nombre" class="form-control" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" name="localidad" placeholder="Localidad" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 col-md-6">
                                <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text" name="telefono" placeholder="Teléfono" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-end">
                                <button onclick="siguiente(this,1)" type="button" class="btn px-5 btn-primary text-uppercase">siguiente</button>
                            </div>
                        </div>
                    </div>
                    <div id="segundo" class="mt-5" style="display:none">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <textarea name="mensaje" placeholder="Mensaje" class="form-control"></textarea>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input required type="file" accept="image/jpeg,application/pdf" value="" name="archivo" class="custom-file-input" id="file">
                                        <label data-invalid="Examinar Adjunto" data-valid="Archivo seleccionado" class="custom-file-label mb-0 text-truncate" data-browse="..." for="file"></label>
                                    </div>
                                </div>
                                <div class="g-recaptcha" data-sitekey="6LfyY50UAAAAAJGHw1v6ixJgvBbUOasaTT6Wz-od"></div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-end">
                                <button onclick="siguiente(this,0)" type="button" class="btn px-5 btn-outline-primary text-uppercase">anterior</button>
                                <button type="submit" class="btn btn-primary text-uppercase px-5 ml-2">enviar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    alertify.defaults.transition = "slide";
    alertify.defaults.theme.ok = "btn btn-primary";
    alertify.defaults.theme.cancel = "btn btn-danger";
    alertify.defaults.theme.input = "form-control";
    if(localStorage.producto !== undefined) {
        let html = "";
        window.producto = JSON.parse(localStorage.producto);
        window.familia = JSON.parse(localStorage.familia);
        window.categoria = JSON.parse(localStorage.categoria);
        link = "{{ URL::to('producto/') }}";
        link += `/${window.producto.url}/${window.producto.id}`;
        html += `<button type="button" class="float-right p-0 m-0 border-0" style="background:transparent; font-weight: 500; font-size: 27px; line-height: 18px; color: #333;" onclick="limpiar(this)"><span aria-hidden="true">&times;</span></button>`;
        html += `Esta por solicitar/consultar sobre el producto ${window.familia.name} / ${window.categoria.name} / <strong>${window.producto.name}</strong><br>`;
        html += `LINK: <a class="alert-link" href="${link}">${window.producto.name}</a>`;
        $("#notificacion").html(html);
        $("#notificacion").removeClass("d-none");
    }
    enviar = function(t) {
        if($("#primero").is(":visible")) {
            siguiente(this,1);
            return false;
        }
        
        let idForm = t.id;
        let url = t.action;
        let method = t.method;
        let promise = new Promise(function (resolve, reject) {
            let formElement = document.getElementById(idForm);
            let request = new XMLHttpRequest();
            let formData = new FormData(formElement);
            if(window.producto !== undefined)
                formData.append("producto_id",window.producto.id);
            request.responseType = 'json';
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( method, url );
            xmlHttp.onload = function() {
                $("form .form-control").attr("disabled",true)
                resolve(xmlHttp.response);
            }
            xmlHttp.send( formData );
        });
        promiseFunction = () => {
            promise
                .then(function(data) {
                    if(parseInt(data) == 1) {
                        alertify.success("Formulario enviado exitosamente");
                        location.reload();
                    } else {
                        alertify.error("Ocurrió un error en el envio. Reintente");
                        $("form .form-control").removeAttr("disabled")
                    }
                }
        )};
        
        alertify.warning("Espere. Enviando formulario");
        promiseFunction();
    };
    siguiente = function(t,tt) {
        if(tt) {
            if($("#nombre").val() == "" || $("#email").val() == "") {
                alertify.error("Complete email y nombre");
                return false;
            }

            $("#primero").hide();
            $("#segundo").show();
            $('.img-1').addClass("inactivo");
            $('.img-2').removeClass("inactivo");
        } else {
            $("#primero").show();
            $("#segundo").hide();
            $('.img-1').removeClass("inactivo");
            $('.img-2').addClass("inactivo");
        }
    };
    limpiar = function(t) {
        
        alertify.confirm("ATENCIÓN","¿Eliminar producto a consultar?",
            function(){
                localStorage.clear();
                $("#notificacion").alert('close');
            },
            function() {}
        ).set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
</script>
@endpush