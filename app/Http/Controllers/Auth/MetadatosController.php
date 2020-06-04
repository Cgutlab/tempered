<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Empresa;
class MetadatosController extends Controller
{
    public function index()
    {
        $title = "Empresa :: Metadatos";
        $view = "";
        $seccion = "metadatos";
        $datos = Empresa::first();
        if(count($datos["metadatos"]) == 0) {
            $aux = ["descripcion" => null,"metas" => null];
            $ARR = [];
            $ARR["home"] = $aux;
            $ARR["empresa"] = $aux;
            $ARR["productos"] = $aux;
            $ARR["preguntas"] = $aux;
            $ARR["presupuesto"] = $aux;
            $ARR["contacto"] = $aux;
            $datos->fill(["metadatos" => $ARR]);
            $datos = $datos->save();
        }
        $data = [
            "title"     => "Empresa :: Metadatos",
            "view"      => "auth.parts.empresa.edit",
            "seccion"   => "metadatos",
            "contenido" => $datos
        ];
        return view('auth.distribuidor',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $seccion = null, $data = null)
    {
        $requestData = $request->all();
        try {
            //code...
            $ARR_data = $data["metadatos"];
            $ARR_data[$seccion]["descripcion"] = $requestData["metadatos_descripcion"] == "" ? null : $requestData["metadatos_descripcion"];
            $ARR_data[$seccion]["metas"] = $requestData["metadatos_metas"] == "" ? null : $requestData["metadatos_metas"];
    
            
            $data->fill(["metadatos" => $ARR_data]);
            $data->save();
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $seccion)
    {
        $metadatos = Empresa::first();
        return self::store($request,$seccion,$metadatos);
    }
}
