@push('styles')
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
@endpush
<div class="alert alert-info" role="alert">
    Textos, imágenes o íconos pertenecientes a la sección. <a class="text-primary" target="blank" href="{{ route('empresa') }}">Ver vista pública de la sección <i class="fas fa-external-link-alt"></i></a>
</div>
<div class="mt-2 wrapper-empresa font-opensans" style="background-image:url({{ asset($data['contenido']['data']['background']['i']) }})">
    <div class="card rounded-0 bg-transparent">
        <div class="card-body rounded-0">
            <div class="row">
                <div class="col-12 col-md-6 texto d-flex align-items-center">
                    <div>
                        {!! $data["contenido"]["data"]["text"] !!}
                    </div>
                </div>
                <div class="col-12 col-md-6 image d-flex align-items-center">
                    <img src="{{ asset($data['contenido']['data']['image']['i']) }}" class="w-100" alt="" srcset="">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="wrapper-form" class="mt-2">
    <form id="form" onsubmit="event.preventDefault(); formSubmit(this);" novalidate class="pt-2" action="{{ url('/adm/contenido/' . $data['section'] . '/update') }}" method="put" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-success text-uppercase px-5 mx-auto d-flex align-items-center mb-5">contenido<i class="fas fa-pencil-alt ml-2"></i></button>
                <div class="container-form"></div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
<script src="http://SortableJS.github.io/Sortable/Sortable.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    window.pyrus = new Pyrus("empresa", null, src);
    window.contenido = @json($data["contenido"]);

    change = function(t) {
        year = $(t).data("year");
        $(".input[data-year].active").removeClass("active");
        $(".text[data-year]").addClass("d-none");
        $(t).addClass("active");
        $(`.text[data-year="${year}"]`).removeClass("d-none");
    };
    formSubmit = function(t) {
        let idForm = t.id;
        let formElement = document.getElementById( idForm );

        let formData = new FormData( formElement );
        formData.append( "ATRIBUTOS", JSON.stringify(
            [
                { DATA: window.pyrus.objetoSimple, TIPO: "U" },
            ]
        ));
        for( let x in CKEDITOR.instances )
            formData.set( x , CKEDITOR.instances[ `${x}` ].getData() );
        formSave( t , formData );
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
        let html = "";
        html += window.pyrus.formulario();
        
        $("#form .container-form").html(html);
        window.pyrus.editor( CKEDITOR );
        setTimeout(() => {
            if(window.contenido.data !== null)
                callbackOK.call(this);
        }, 50);
    }
    /** */
    init(function() {
        window.pyrus.show( CKEDITOR , `{{ asset('/') }}` , window.contenido.data );
    });
</script>
@endpush