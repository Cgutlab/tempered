<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Empresa;
use App\Producto;
use App\Contenido;
use App\Slider;
use App\Familia;
use App\Categoria;
use App\Descarga;
class GeneralController extends Controller
{
    public function datos() {
        $datos = Empresa::first();
        $whatsapp = $telefono = $email = "";
        $email = $datos["email"][0];
        for($i = 0 ; $i < count($datos["telefono"]) ; $i++) {
            if($datos["telefono"][$i]["tipo"] != "wha") continue;
            $whatsapp = $datos["telefono"][$i]["telefono"];
            break;
        }
        for($i = 0 ; $i < count($datos["telefono"]) ; $i++) {
            if($datos["telefono"][$i]["tipo"] != "tel") continue;
            $telefono = $datos["telefono"][$i];
            break;
        }

        $data = [
            "empresa"   => $datos,
            "whatsapp"  => $whatsapp,
            "email"  => $email,
            "telefono"  => $telefono,
            "menu"  => Familia::whereNull( 'categoria_id' )->where( "elim", 0 )->orderBy( 'order' )->get()
        ];
        return $data;
    }
    public function index() {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "HOME";
        $data[ "view" ] = "page.parts.index";
        $data[ "productos" ] = Producto::where( "is_destacado" , 1 )->where( "elim", 0 )->orderBy( "order" )->get();
        $data[ "sliders" ] = Slider::where( 'section' , 'home' )->where( "elim", 0 )->orderBy( "order" )->get();
        $data[ "contenido" ] = Contenido::where( 'section' , 'home' )->first();
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
    public function empresa() {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "EMPRESA";
        $data[ "view" ] = "page.parts.empresa";
        $data[ "sliders" ] = Slider::where( 'section' , 'empresa' )->where( "elim", 0 )->orderBy( "order" )->get();
        $data[ "contenido" ] = Contenido::where( 'section' , 'empresa' )->first();
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
    public function productos() {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "PRODUCTOS";
        $data[ "view" ] = "page.parts.familia";
        $data[ "familias" ] = Familia::whereNull( 'categoria_id' )->where( "elim", 0 )->orderBy( 'order' )->get();
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
    public function familia( $url , $id ) {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "PRODUCTOS";
        $data[ "view" ] = "page.parts.categoria";
        $data[ "familias" ] = Familia::whereNull( 'categoria_id' )->where( "elim", 0 )->orderBy( 'order' )->get();
        $data[ "familia" ] = Familia::find( $id );
        if($data[ "familia" ]->categorias()->get()->count() == 1) {
            $categoria = $data[ "familia" ]->categorias()->get()[0];
            if($categoria->productos()->count() == 1) {
                $data[ "title" ] = "PRODUCTO";
                $data[ "view" ] = "page.parts.producto";
                //$data[ "familias" ] = Familia::whereNull( 'categoria_id' )->where( "elim", 0 )->orderBy( 'order' )->get();
                $data[ "producto" ] = $categoria->productos[0];
                $data[ "categoria" ] = $data[ "producto" ]->categoria;
                $data[ "familia" ] = $data[ "categoria" ]->familia;
            }
        }
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
    public function categoria( $url_f , $url , $id ) {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "PRODUCTOS";
        $data[ "view" ] = "page.parts.categoria";
        $data[ "familias" ] = Familia::whereNull( 'categoria_id' )->where( "elim", 0 )->orderBy( 'order' )->get();
        $data[ "categoria" ] = Categoria::find( $id );
        $data[ "familia" ] = $data[ "categoria" ]->familia;
        if($data[ "categoria" ]->productos()->count() == 1) {

            $data[ "title" ] = "PRODUCTO";
            $data[ "view" ] = "page.parts.producto";
            //$data[ "familias" ] = Familia::whereNull( 'categoria_id' )->where( "elim", 0 )->orderBy( 'order' )->get();
            $data[ "producto" ] = $data[ "categoria" ]->productos[0];
            $data[ "categoria" ] = $data[ "producto" ]->categoria;
            $data[ "familia" ] = $data[ "categoria" ]->familia;
        }
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
    public function producto( $url , $id ) {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "PRODUCTO";
        $data[ "view" ] = "page.parts.producto";
        $data[ "familias" ] = Familia::whereNull( 'categoria_id' )->where( "elim", 0 )->orderBy( 'order' )->get();
        $data[ "producto" ] = Producto::find( $id );
        $data[ "categoria" ] = $data[ "producto" ]->categoria;
        $data[ "familia" ] = $data[ "categoria" ]->familia;
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
    public function contacto() {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "CONTACTO";
        $data[ "view" ] = "page.parts.contacto";
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
    public function presupuesto() {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "SOLICITUD DE PRESUPUESTO";
        $data[ "view" ] = "page.parts.presupuesto";
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
    public function descargas() {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "DESCARGAS";
        $data[ "view" ] = "page.parts.descarga";
        $data[ "descargas" ] = Descarga::where( 'type','pública' )->where( "elim", 0 )->orderBy( 'order' )->get();
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
}
