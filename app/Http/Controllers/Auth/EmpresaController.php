<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Empresa;
use App\Contenido;
class EmpresaController extends Controller
{
    public function datos()
    {
        $datos = Empresa::first();

        if(empty($datos)) {
            $datos = Empresa::create([
                "email"     => [],
                "telefono"  => [],
                "domicilio" => [],
                "redes"     => [],
                "images" => [],
                "metadatos" => [],
                "form" => [],
                "secciones"
            ]);
        }
        
        $data = [
            "title"     => "Empresa :: Datos generales",
            "view"      => "auth.parts.empresa.edit",
            "seccion"   => "empresa",
            "contenido" => $datos
        ];
        return view('auth.distribuidor',compact('data'));
    }

    public function form(Request $request)
    {
        $dataRequest = $request->all();
        $datos = Empresa::first();
        if(empty($dataRequest)) {
            $data = [
                "title"     => "Empresa :: Email de formularios",
                "view"      => "auth.parts.empresa.edit",
                "seccion"   => "form",
                "contenido" => $datos
            ];
            return view('auth.distribuidor',compact('data'));
        }
        try {
            unset($dataRequest["_token"]);
            $datos->fill(["form" => $dataRequest]);
            $datos->save();
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    
    public function update(Request $request)
    {
        $datos = Empresa::first();
        $datosRequest = $request->all();
        $datosRequest["ATRIBUTOS"] = json_decode( $datosRequest["ATRIBUTOS"] , true );
        //dd($datosRequest[ "empresa_telefono_telefono_is_link_input" ]);
        //try {

            $OBJ = [];
            for( $x = 0 ; $x < count($datosRequest["ATRIBUTOS"]) ; $x++ ) {
                $aux = $datosRequest["ATRIBUTOS"][$x];
                if(!isset($OBJ[$aux["PALABRA"]]))
                    $OBJ[$aux["PALABRA"]] = [];
                if($aux["TIPO"] == "U") {//UNICO
                    foreach($aux["DATA"]["especificacion"] AS $nombre => $tipo) {
                        $E_aux = null;
                        if(isset($datosRequest["{$aux["DATA"]["name"]}_{$nombre}"])) {
                            if($tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                $path = public_path("images/empresa");
                                if (!file_exists($path))
                                    mkdir($path, 0777, true);
                                
                                if(!empty($datos[$aux["PALABRA"]]))
                                    $E_aux = $datos[$aux["PALABRA"]][$nombre];
                                $file = $request->file("{$aux["DATA"]["name"]}_{$nombre}");
                                if(!is_null($file)) {
                                    $imageName = "{$nombre}." .$file->getClientOriginalExtension();
                                    $file->move($path, $imageName);
                                    $E_aux = [
                                        "e" => $file->getClientOriginalExtension(),
                                        "i" => "images/empresa/{$imageName}"
                                    ];
                                }
                            } else {
                                if(isset($datosRequest["{$aux["DATA"]["name"]}_{$nombre}"]))
                                    $E_aux = $datosRequest["{$aux["DATA"]["name"]}_{$nombre}"];
                            }
                        } else {
                            if($tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                if(isset($aux["PALABRA"])) {
                                    if( isset( $datos[$aux["PALABRA"]][$nombre] ))
                                        $E_aux = $datos[$aux["PALABRA"]][$nombre];
                                } else
                                    $E_aux = $datos[$nombre];
                            }
                        }

                        if(isset($aux["PALABRA"]))
                            $OBJ[$aux["PALABRA"]][$nombre] = $E_aux;
                        else
                            $OBJ[$nombre] = $E_aux;
                    }
                } else {//M
                    /**
                     * Si el parámetro bucle esta presente, indica que la entidad tiene más de 1 
                     * valor a guardar
                     */
                    if(isset($aux["BUCLE"])) {
                        for( $i = 0 ; $i < count($datosRequest[$aux["BUCLE"]]) ; $i++ ) {
                            $OBJ_AUX = [];
                            foreach($aux["DATA"]["especificacion"] AS $nombre => $tipo) {
                                $E_aux = null;
                                if(isset($datosRequest[ "{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}" ])) {
                                    if( $tipo == "TP_CHECK" ) {
                                        //dd($datosRequest[ "{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}_input" ][ $i ]);
                                        if( !empty( $datosRequest[ "{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}_input" ][ $i ] ) )
                                            $E_aux = $datosRequest[ "{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}" ][ $i ];
                                        //dd($E_aux);
                                    } else if($tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                        $path = public_path("images/empresa");
                                        if (!file_exists($path))
                                            mkdir($path, 0777, true);
                                        
                                        if(!empty($datos[$aux["PALABRA"]]))
                                            $E_aux = $datos[$aux["PALABRA"]][$nombre];
                                        $file = $request->file("{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}");
                                        if( !is_null( $file )) {
                                            if( isset( $file[$i] ) )
                                            $imageName = "{$nombre}." .$file[$i]->getClientOriginalExtension();
                                            $file[$i]->move($path, $imageName);
                                            $E_aux = [
                                                "e" => $file[$i]->getClientOriginalExtension(),
                                                "i" => "images/empresa/{$imageName}"
                                            ];
                                        }
                                    } else {
                                        if(isset($datosRequest["{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}"][$i]))
                                            $E_aux = $datosRequest["{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}"][$i];
                                    }
                                } else {
                                    if($tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                        if(isset($aux["PALABRA"]))
                                            $E_aux = $datos[$aux["PALABRA"]][$nombre];
                                        else
                                            $E_aux = $datos[$nombre];
                                    }
                                }
                                $OBJ_AUX[$nombre] = $E_aux;
                            }
                            if(isset($aux["PALABRA"]))
                                $OBJ[$aux["PALABRA"]][] = $OBJ_AUX;
                            else
                                $OBJ[] = $OBJ_AUX;
                        }
                        //dd($OBJ);
                    } else {
                        $OBJ_AUX = [];
                        foreach($aux["DATA"]["especificacion"] AS $nombre => $tipo) {
                            if(isset($datosRequest["{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}"]))
                                $OBJ_AUX = $datosRequest["{$aux["DATA"]["name"]}_{$aux["PALABRA"]}_{$nombre}"];
                        }
                        $OBJ[$aux["PALABRA"]] = $OBJ_AUX;
                    }
                }
            }

            $datos->fill($OBJ);
            $datos->save();
            //dd($datos);
            return 1;
        /*} catch (\Throwable $th) {
            return 0;
        }*/
    }
    /**
     * Redes sociales
     */
    public function redes() {
        $datos = Empresa::first()["redes"];

        $data = [
            "title"     => "Empresa :: Redes sociales",
            "view"      => "auth.parts.empresa.edit",
            "seccion"   => "redes",
            "contenido" => $datos
        ];
        return view('auth.distribuidor',compact('data'));
    }
    public function redesStore(Request $request, $id = null) {
        $datos = Empresa::first();
        $requestData = $request->all();
        $requestData["ATRIBUTOS"] = json_decode( $requestData["ATRIBUTOS"] , true );
        //dd($requestData);
        try {
            $redes = $datos["redes"];
            if(is_null($id))
                $id = time();
            if(empty($redes))
                $redes = [];
            else {
                if(!isset($redes[$id]))
                    $redes[$id] = [];
            }
    
            $OBJ = [];
            for($i = 0 ; $i < count($requestData["ATRIBUTOS"]) ; $i++) {
                $aux = $requestData["ATRIBUTOS"][$i];
                foreach($aux["DATA"]["especificacion"] AS $nombre => $tipo) {
                    $E_aux = $requestData["{$aux["DATA"]["name"]}_{$nombre}"];
                    $OBJ[$nombre] = $E_aux;
                }
            }
            $redes[$id] = $OBJ;
    
            $datos->fill(["redes" => $redes]);
            $datos->save();
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public function redesDestroy($id)
    {
        $ARR_data = [];
        $datos = Empresa::first();
        $redes = $datos["redes"];
        unset($redes[$id]);

        $ARR_data["redes"] = $redes;
        $datos->fill($ARR_data);
        $datos->save();
        return 1;
    }
    public function redesUpdate(Request $request, $id) {
        return self::redesStore($request,$id);
    }
    public function terminos(Request $request) {
        $contenido = Contenido::where("section","terminos")->first();
        if(empty($contenido))
            $contenido = Contenido::create(["section" => "terminos", "data" => [ "titulo" => null, "texto" => null ]]);
        $data = [
            "title"     => "Contenido: TÉRMINOS Y CONDICIONES",
            "view"      => "auth.parts.contenido",
            "section"   => "terminos",
            "contenido" => $contenido
        ];
        return view('auth.distribuidor',compact('data'));
    }
}
