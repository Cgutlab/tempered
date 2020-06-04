<?php

namespace App\Http\Controllers\PrivateArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Descarga;
use App\Familia;
use App\Empresa;
class UserController extends Controller
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
    public function descargas() {
        $data = self::datos();
        $data[ "metadato" ] = [
            "description" => "",
            "keywords" => ""
        ];
        $data[ "title" ] = "DESCARGAS";
        $data[ "view" ] = "page.parts.descargas";
        $data[ "descargas" ] = Descarga::where( 'type','privada' )->where( 'section', auth()->guard('client')->user()['type'] )->where( "elim", 0 )->orderBy( 'order' )->get();
        return view( 'page.distribuidor' ,compact( 'data' ) );
    }
}
