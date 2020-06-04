<div class="alert alert-info" role="alert">
    Textos, imágenes o íconos pertenecientes a la sección. <a class="text-primary" target="blank" href="{{ route('calidad') }}">Ver vista pública de la sección <i class="fas fa-external-link-alt"></i></a>
</div>
<div id="wrapper-form" class="mt-2">
    <div class="card">
        <div class="card-body">
            <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/contenido/' . $data['seccion'] . '/update') }}" method="put" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-success text-uppercase px-5 mx-auto d-flex align-items-center mb-5 rounded-pill">contenido<i class="fas fa-save ml-2"></i></button>
                <div class="container-form"></div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    $(document).on("ready",function() {
        $(".ckeditor").each(function () {
            CKEDITOR.replace( $(this).attr("name") );
        });
    });

    const src = "{{ asset('images/general/no-img.png') }}";
    window.pyrus = new Pyrus("certificados", null, src);
    window.pyrus_certificado = new Pyrus("certificado", null, src);
    window.contenido = @json($data["contenido"]);

    formSubmit = function(t) {
        let idForm = t.id;
        let url = t.action;
        let formElement = document.getElementById(idForm);
        let formData = new FormData(formElement);
        formData.append("ATRIBUTOS",JSON.stringify(window.pyrus.objetoSimple));
        formData.append("ATRIBUTOSIMAGES",JSON.stringify(window.pyrus_certificado.objetoSimple));

        for(let x in window.pyrus.especificacion) {
            if(window.pyrus.especificacion[x].EDITOR === undefined) continue;
            if(window.pyrus.objeto.JSON !== undefined) {
                if(window.pyrus.objeto.JSON[x] !== undefined) {
                    for(let i in window.pyrus.objeto.JSON[x])
                        formData.set(`${x}_${i}`, CKEDITOR.instances[`${x}_${i}`].getData());
                    continue;
                } else {
                    if(CKEDITOR.instances[`${x}`] !== undefined)
                        formData.set(x,CKEDITOR.instances[`${x}`].getData());
                }
            } else {
                if(CKEDITOR.instances[`${x}`] !== undefined)
                    formData.set(x,CKEDITOR.instances[`${x}`].getData());
            }
        }
        
        alertify.warning("Espere. Guardando contenido");
        axios({
            method: "post",
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
    removeImage = function(t) {
        if($(t).parent().find(`.imageURL_hidden`).val() == "") {
            $(t).parent().remove();
            return false;
        }
        $(t).parent().find(`.imageDELETE_hidden`).val("1");
        $(t).parent().addClass("d-none");
    };
    addImage = function(t, data = null) {
        if(window.countImage === undefined) window.countImage = 0;
        window.countImage ++;
        let html = "";
        html += `<div class="col-12 my-2 position-relative">`;
            html += `<i onclick="removeImage(this);" class="fas fa-times-circle position-absolute" style="top: -5px; right: 10px; z-index: 11; cursor: pointer;"></i>`;
            html += `<input type="hidden" class="imageURL_hidden" name="imageURL[]" value="" />`;
            html += `<input type="hidden" class="imageDELETE_hidden" name="imageDELETE[]" value="0" />`;
            html += `${window.pyrus_certificado.formulario(window.countImage,"image")}`;
        html += `</div>`;
        $("#targetImagenes").append(html);

        if(data !== null) {
            target = $("#targetImagenes").find("> div:last-child");
            //srcIMG = `{{ asset('${data.image}') }}`;
            //target.find("img").attr("src",srcIMG);
            target.find(`.imageDELETE_hidden`).val(0);
            target.find(`.imageURL_hidden`).val(data.image);

            for(let x in window.pyrus_certificado.especificacion) {
                if(window.pyrus_certificado.especificacion[x].TIPO == "TP_FILE") {
                    date = new Date();
                    img = `{{ asset('${data[x]}') }}?t=${date.getTime()}`;
                    $(`#src-image_${x}_${window.countImage}`).attr("src",img);
                    continue;
                }
                if(window.pyrus_certificado.objeto.JSON !== undefined) {
                    if(window.pyrus_certificado.objeto.JSON[x] !== undefined) {
                        for(let i in window.pyrus_certificado.objeto.JSON[x])
                            CKEDITOR.instances[`${x}_${i}`].setData(data[x][i]);
                        continue;
                    }
                }
                $(`#image_${x}_${window.countImage}`).val(data[x]);
            }
        }
    };
    /** ------------------------------------- */
    init = function(callbackOK) {
        console.log("CONSTRUYENDO FORMULARIO Y TABLA");
        /** */
        let html = `<button type="button" class="btn btn-dark px-5 text-uppercase d-block mx-auto mb-3 text-uppercase rounded-pill" onclick="addImage(this)">imagen<i class="fas fa-image ml-3"></i></button><div id="targetImagenes" class="row"></div>`;
        $("#form .container-form").html(window.pyrus.formulario());
        $("#form .container-form").find(".frm-imagen").html(html);
        setTimeout(() => {
            callbackOK.call(this);
        }, 50);
    }
    /** */
    init(function() {
        for(let x in window.pyrus.especificacion) {
            $(`[name="${x}"]`).val(window.contenido.data[x]);
        }
        window.contenido.data.images.forEach( function( i ) {
            addImage( null, i );
        });
        //$(`[name="titulo_es"]`).val(window.contenido.data.CONTENIDO.titulo.es);
    });
</script>
@endpush