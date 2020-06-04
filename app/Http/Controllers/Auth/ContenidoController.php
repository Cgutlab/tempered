<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contenido;
use App\Empresa;
class ContenidoController extends Controller
{
    public function edit( $seccion ) {
        //dd($seccion);
        $contenido = Contenido::where( "section" , $seccion )->first();
        
        $OBJ = [
            "section" => $seccion,
            "data" => null
        ];

        if( empty( $contenido ) ) {
            switch( $seccion ) {
                case "home":
                    $OBJ["data"] = [];
                    $OBJ["data"]["iconos"] = [];
                break;
                case "empresa":
                    $OBJ["data"] = [];
                    $OBJ["data"]["text"] = null;
                    $OBJ["data"]["image"] = null;
                    $OBJ["data"]["background"] = null;
                break;
            }
            
            $contenido = Contenido::create( $OBJ );
        }
    
        $data = [
            "view"          => "auth.parts.contenido",
            "title"         => "Contenido: " . strtoupper( $seccion ),
            "section"       => $seccion,
            "contenido"     => $contenido,
            "empresa"       => Empresa::first()
        ];
        return view( 'auth.distribuidor' , compact( 'data' ) );
    }

    public function update( Request $request , $seccion ) {
        $datosRequest = $request->all();
        
        //try {
            $contenido = Contenido::where('section',$seccion)->first();
            if( empty( $contenido ) ) {
                $contenido = Contenido::create(
                    [
                        "section" => "{$seccion}",
                        "content" => []
                    ]
                );
            }
            $OBJ = (new AdmController)->object( $request , $contenido->data , [ "iconos_text" , "icon_text" ] );
            //dd($OBJ);
            $contenido->fill( [ "data" => $OBJ] );
            $contenido->save();
            //dd($contenido);
            echo 1;
        /*} catch (\Throwable $th) {
            return 0;
        }*/
    }
}
