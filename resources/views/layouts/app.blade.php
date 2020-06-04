<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('headTitle')</title>
    <link href="{{ asset('favicon.png') }}" rel="icon" type="image/png">
    <!-- <Styles> -->
    @include('layouts.general.css')
    @stack('styles')
    <!-- </Styles> -->
</head>
<body>
    <div class="modal fade" id="modalCombinacion" tabindex="-1" role="dialog" aria-labelledby="modalCombinacion" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Combinaciones de teclas del sistema</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td class="border-top-0">Alt+Ctrl+S</td>
                                <td class="border-top-0">Guardar elemento: guarda el contenido del elemento del formulario abierto</td>
                            </tr>
                            <tr>
                                <td>Alt+Ctrl+N</td>
                                <td>Abre o cierra el formulario: solo para secciones que tengan varios registros</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.general.message')
    @yield('content')
    <!-- Scripts -->
    @include('layouts.general.script')
    <script>
        window.url = "{{ url()->current() }}";
        const src = "{{ asset('images/general/no-img.gif') }}";
        @isset( $data )
        urlAUX = @json($data);
        if( urlAUX.url !== undefined )
            window.url = urlAUX.url;
        @endisset
        $(document).ready(function() {
            $("body").on("click",".alert button.close", function() {
                $(this).closest(".alert").remove();
            });

            if($("#sidebar").find(`a[href="${window.url}"]`).data("link") == "u") {
                $("#sidebar").find(`a[href="${window.url}"]`).addClass("active");
                $("#sidebar").find(`a[href="${window.url}"]`).closest("ul").addClass("show");
                $("#sidebar").find(`a[href="${window.url}"]`).closest("ul").prev().attr("aria-expanded",true).parent().addClass("active");
            } else
                $("#sidebar").find(`a[href="${window.url}"]`).parent().addClass("active");
        });
    </script>
    @stack('scripts')
</body>
</html>
