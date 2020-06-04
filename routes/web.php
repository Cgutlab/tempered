<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ 'uses' => 'Page\GeneralController@index', 'as' => 'index' ]);
Route::get('contacto', [ 'uses' => 'Page\GeneralController@contacto', 'as' => 'contacto' ]);
Route::get('presupuesto', [ 'uses' => 'Page\GeneralController@presupuesto', 'as' => 'presupuesto' ]);
Route::get('empresa', [ 'uses' => 'Page\GeneralController@empresa', 'as' => 'empresa' ]);
Route::get('productos', [ 'uses' => 'Page\GeneralController@productos', 'as' => 'productos' ]);
Route::get('familia/{url}/{id}', [ 'uses' => 'Page\GeneralController@familia', 'as' => 'familia' ]);
Route::get('categoria/{url_f}/{url}/{id}', [ 'uses' => 'Page\GeneralController@categoria', 'as' => 'categoria' ]);
Route::get('producto/{url}/{id}', [ 'uses' => 'Page\GeneralController@producto', 'as' => 'producto' ]);
Route::get('descargas', [ 'uses' => 'Page\GeneralController@descargas', 'as' => 'descargas' ]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('salir', 'PrivateArea\LoginController@salir')->name('salir');
Route::group(['prefix' => 'cliente', 'as' => 'client.'], function() {
    Route::get('changeClave/{id}', 'PrivateArea\UsuarioController@changeClave')->name('changeClave');
    Route::post('changepass','PrivateArea\UsuarioController@changepass')->name("changepass");
    Route::post('acceso', 'PrivateArea\LoginController@login')->name("acceso");

    Route::get('descargas', 'PrivateArea\UserController@descargas')->name("descargas");
});

Route::group(['middleware' => 'auth', 'prefix' => 'adm'], function() {
    Route::get('/', 'Auth\AdmController@index')->name('adm');
    Route::get('logout', ['uses' => 'Auth\AdmController@logout' , 'as' => 'adm.logout']);

    /**
     * CONTENIDO
     */
    Route::group(['prefix' => 'contenido', 'as' => 'contenido'], function() {
        Route::get('{seccion}/edit', ['uses' => 'Auth\ContenidoController@edit', 'as' => '.edit']);
        Route::post('{seccion}/update', ['uses' => 'Auth\ContenidoController@update', 'as' => 'update']);
    });

    /**
     * DESCARGAS
     */
    Route::resource('descargas', 'Auth\DescargaController')->except(['update','index','show']);
    Route::get('descargas/{tipo}', ['uses' => 'Auth\DescargaController@index', 'as' => 'descargas.index']);
    Route::post('descargas/update/{id}', ['uses' => 'Auth\DescargaController@update', 'as' => 'descargas.update']);
    /**
     * DESCARGAS PARTES
     */
    Route::resource('descargapartes', 'Auth\DescargasController')->except(['index','update']);
    Route::get('descarga/{url}/{id}', ['uses' => 'Auth\DescargasController@index', 'as' => 'descargapartes.index']);
    Route::post('descargapartes/update/{id}', ['uses' => 'Auth\DescargasController@update', 'as' => 'descargapartes.update']);

    /**
     * FAMILIAS
     */
    Route::resource('familias', 'Auth\FamiliaController')->except(['update']);
    Route::post('familias/update/{id}', ['uses' => 'Auth\FamiliaController@update', 'as' => 'familias.update']);
    /**
     * CATEGORIAS
     */
    Route::resource('categorias', 'Auth\CategoriaController')->except(['index','update']);
    Route::get('familia/{url}/{id}', ['uses' => 'Auth\CategoriaController@index', 'as' => 'categorias.index']);
    Route::post('categorias/update/{id}', ['uses' => 'Auth\CategoriaController@update', 'as' => 'categorias.update']);
    /**
     * PRODUCTOS
     */
    Route::resource('productos', 'Auth\ProductoController')->except(['index','update','show']);
    Route::get('productos', ['uses' => 'Auth\ProductoController@all', 'as' => 'productos.all']);
    Route::get('familia/{url}/{url_f}/{id}', ['uses' => 'Auth\ProductoController@index', 'as' => 'productos.index']);
    Route::get('productos/deleteDoc/{id}', ['uses' => 'Auth\ProductoController@deleteDoc', 'as' => 'productos.deleteDoc']);
    Route::post('productos/update/{id}', ['uses' => 'Auth\ProductoController@update', 'as' => 'productos.update']);
    Route::match(['get', 'post'], 'productos/destacados', [ 'uses' => 'Auth\ProductoController@destacados', 'as' => 'productos.destacados' ]);

    Route::resource('productoimage', 'Auth\ProductoimageController')->except(['index','update']);
    Route::get('producto/{url}/{id}', ['uses' => 'Auth\ProductoimageController@index', 'as' => 'productoimage.index']);
    Route::post('productoimage/update/{id}', ['uses' => 'Auth\ProductoimageController@update', 'as' => 'productoimage.update']);
    /**
     * SLIDERS
     */
    Route::resource('slider', 'Auth\SliderController')->except(['index','update','show']);
    Route::get('slider/{seccion}', ['uses' => 'Auth\SliderController@index', 'as' => 'slider.index']);
    Route::post('slider/update/{id}', ['uses' => 'Auth\SliderController@update', 'as' => 'slider.update']);

    /**
     * CLIENTES
     */
    Route::resource('clientes', 'Auth\ClienteController')->except(['index','update']);
    Route::match(['get', 'post'], 'clientes', [ 'uses' => 'Auth\ClienteController@index', 'as' => 'clientes.index' ]);
    Route::post('clientes/update/{id}', ['uses' => 'Auth\ClienteController@update', 'as' => 'clientes.update']);
    /**
     * DATOS
     */
    Route::group(['prefix' => 'empresa', 'as' => 'empresa'], function() {
        Route::get('datos', ['uses' => 'Auth\EmpresaController@datos', 'as' => '.datos']);
        Route::match(['get', 'post'], 'terminos',['as' => '.terminos','uses' => 'Auth\EmpresaController@terminos' ]);
        Route::match(['get', 'post'], 'form',['as' => '.form','uses' => 'Auth\EmpresaController@form' ]);
        Route::post('update', ['uses' => 'Auth\EmpresaController@update', 'as' => '.update']);

        Route::group(['prefix' => 'metadatos', 'as' => '.metadatos'], function() {
            Route::get('/', ['uses' => 'Auth\MetadatosController@index', 'as' => '.index']);
            Route::get('edit/{page}', ['uses' => 'Auth\MetadatosController@edit', 'as' => '.edit']);
            Route::post('update/{page}', ['uses' => 'Auth\MetadatosController@update', 'as' => '.update']);
            Route::post('store', ['uses' => 'Auth\MetadatosController@store', 'as' => '.store']);
            Route::get('delete/{page}', ['uses' => 'Auth\MetadatosController@destroy', 'as' => '.destroy']);
        });
        Route::group(['prefix' => 'usuarios', 'as' => '.usuarios'], function() {
            Route::get('/', ['uses' => 'Auth\UserController@index', 'as' => '.index']);
            Route::get('datos', ['uses' => 'Auth\UserController@datos', 'as' => '.datos']);
            Route::get('edit/{id}', ['uses' => 'Auth\UserController@edit', 'as' => '.edit']);
            Route::post('update/{id}', ['uses' => 'Auth\UserController@update', 'as' => '.update']);
            Route::post('store', ['uses' => 'Auth\UserController@store', 'as' => '.store']);
            Route::get('delete/{id}', ['uses' => 'Auth\UserController@destroy', 'as' => '.destroy']);
        });
        Route::group(['prefix' => 'redes', 'as' => '.redes'], function() {
            Route::get('/', ['uses' => 'Auth\EmpresaController@redes', 'as' => '.index']);
            Route::post('update/{id}', ['uses' => 'Auth\EmpresaController@redesUpdate', 'as' => '.update']);
            Route::post('store', ['uses' => 'Auth\EmpresaController@redesStore', 'as' => '.store']);
            Route::get('delete/{id}', ['uses' => 'Auth\EmpresaController@redesDestroy', 'as' => '.destroy']);
        });
    });
});