alertify.defaults.transition = "slide";
alertify.defaults.theme.ok = "btn btn-primary";
alertify.defaults.theme.cancel = "btn btn-danger";
alertify.defaults.theme.input = "form-control";
/** ------------------------------------- */
/** ------------------------------------- */
readURL = function(input, target) {
    console.log(input)
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(`#${target}`).attr(`src`,`${e.target.result}`);
        };
        reader.readAsDataURL(input.files[0]);
    }
};
check = function( i ) {
    if( $( i ).prop( "checked" ) )
        $( i ).find( "+ input").val( 1 );
    else
        $( i ).find( "+ input").val( 0 );
};
formSave = function( t , formData , phrases = null ) {
    let url = t.action;
    let method = t.method;

    method = (method == "GET" || method == "get") ? "post" : method;

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
            ph = "Contenido guardado";
            if( phrases !== null ) {
                if( phrases.success !== undefined )
                    ph = phrases.success;
            }
            
            alertify.success( ph );
            
            location.reload();
        } else {
            ph = "Ocurrió un error en el guardado. Reintente";
            if( phrases !== null ) {
                if( phrases.error !== undefined )
                    ph = phrases.error;
            }

            alertify.error( ph );
        }
    })
    .catch(function(err) {
        ph = "Ocurrió un error en el guardado.";
        if( phrases !== null ) {
            if( phrases.err !== undefined )
                ph = phrases.err;
        }

        alertify.error( ph );
    })
    .then(function() {});
};