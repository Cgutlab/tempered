<?php
/**
 * 
 */
define( "MENU" ,
[
    [
        "id"        => "home",
        "nombre"    => "Home",
        "icono"     => "<i class='nav-icon fas fa-home'></i>",
        "submenu"   => [
            [
                "nombre"    => "Contenido",
                "icono"     => "<i class='nav-icon fas fa-file-contract'></i>",
                "url"       => route('contenido.edit', ['seccion' => 'home'])
            ],[
                "nombre"    => "Productos destacados",
                "icono"     => '<i class="fas fa-star"></i>',
                "url"       => route('productos.destacados')
            ],[
                "nombre"    => "Slider",
                "icono"     => '<i class="far fa-images"></i>',
                "url"       => route('slider.index', ['seccion' => 'home'])
            ]
        ],
        "ok"        => 1
    ],
    [
        "id"        => "empresa",
        "nombre"    => "Empresa",
        "icono"     => "<i class='nav-icon fas fa-building'></i>",
        "submenu"   => [
            [
                "nombre"    => "Contenido",
                "icono"     => "<i class='nav-icon fas fa-file-contract'></i>",
                "url"       => route('contenido.edit', ['seccion' => 'empresa'])
            ],[
                "nombre"    => "Slider",
                "icono"     => '<i class="far fa-images"></i>',
                "url"       => route('slider.index', ['seccion' => 'empresa'])
            ]
        ],
        "ok"        => 1
    ],
    [
        "id"        => "productos",
        "nombre"    => "Productos",
        "icono"     => "<i class='nav-icon fas fa-industry'></i>",
        "url"       => route('familias.index'),
        /*"submenu"   => [
            [
                "nombre"    => "Familia",
                "icono"     => '<i class="fas fa-warehouse"></i>',
            ],[
                "nombre"    => "Todos",
                "icono"     => '<i class="fas fa-cube"></i>',
                "url"       => route('productos.all')
            ]
        ],*/
        "ok"        => 1
    ],
    [
        "id"        => "descargas",
        "nombre"    => "Descargas",
        "icono"     => '<i class="fas fa-download"></i>',
        "submenu"   => [
            [
                "nombre"    => "Pública",
                "icono"     => '<i class="fas fa-lock-open"></i>',
                "url"       => route('descargas.index', ['tipo' => 'pública'])
            ],[
                "nombre"    => "Privada",
                "icono"     => '<i class="fas fa-lock"></i>',
                "url"       => route('descargas.index', ['tipo' => 'privada'])
            ]
        ],
        "ok"        => 1
    ],
    [
        "separar"   => 1
    ],
    [
        "id"        => "clientes",
        "nombre"    => "Clientes",
        "icono"     => '<i class="fas fa-users"></i>',
        "url"       => route('clientes.index'),
        "ok"        => 1
    ]
]
);