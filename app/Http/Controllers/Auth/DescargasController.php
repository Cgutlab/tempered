<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Descarga;
use App\Descargas;
class DescargasController extends Controller
{
    public $idioma = ["es","en","it"];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $url, $id )
    {
        $descarga = Descarga::find( $id );
        $descargas = $descarga->partes()->orderBy( 'order' )->get();
        $data = [
            "view"      => "auth.parts.descargas",
            "title"     => "Descarga",
            "descarga"      => $descarga,
            "descargas"   => $descargas,
            "breadcrumb" => '<li class="breadcrumb-item"><a href="' . route('descargas.index', [ 'tipo' => $descarga[ "type" ] ]) . '">Descarga: ' . $descarga["name"] . '</a></li><li class="breadcrumb-item active" aria-current="page">Partes</li>',
        ];
        
        return view('auth.distribuidor',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $data = null)
    {
        $OBJ = [];
        $datosRequest = $request->all();
        //dd($datosRequest);
        try {
            $datosRequest["ATRIBUTOS"] = json_decode( $datosRequest["ATRIBUTOS"] , true );
            $OBJ = [];
            for( $x = 0 ; $x < count($datosRequest["ATRIBUTOS"]) ; $x++ ) {
                $aux = $datosRequest["ATRIBUTOS"][$x];
                if($aux["TIPO"] == "U") {//UNICO
                    foreach($aux["DATA"]["especificacion"] AS $nombre => $tipo) {
                        $E_aux = null;
                        if(isset($datosRequest["{$aux["DATA"]["name"]}_{$nombre}"])) {
                            if($tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                $path = public_path("archivos/descarga");
                                if (!file_exists($path))
                                    mkdir($path, 0777, true);
                                
                                if(!empty($contenido["data"]))
                                    $E_aux = $contenido["data"][$aux["PALABRA"]][$nombre];
                                $file = $request->file("{$aux["DATA"]["name"]}_{$nombre}");
                                if(!is_null($file)) {
                                    if(!empty( $data[ $nombre ] )) {
                                        $filename = public_path() . "/{$data[ $nombre ]}";
                                        if ( file_exists( $filename ) )
                                            unlink( $filename );
                                    }
                                    $OBJ["ext"] = $file->getClientOriginalExtension();
                                    $imageName = time() . "." .$file->getClientOriginalExtension();
                                    $file->move($path, $imageName);
                                    $E_aux = "archivos/descarga/{$imageName}";
                                }
                            } else {
                                if(isset($datosRequest["{$aux["DATA"]["name"]}_{$nombre}"]))
                                    $E_aux = $datosRequest["{$aux["DATA"]["name"]}_{$nombre}"];
                            }
                        } else {
                            if( !empty( $data ) ) {
                                if($tipo == "TP_FILE" || $tipo == "TP_IMAGE")
                                    $E_aux = $data[$nombre];
                            }
                        }
                        
                        if(isset($aux["PALABRA"]))
                            $OBJ[$aux["PALABRA"]][$nombre] = $E_aux;
                        else
                            $OBJ[$nombre] = $E_aux;
                    }
                }
            }
            $OBJ["url"] = str_slug( $OBJ["name"] );
            if(is_null($data)) {
                Descargas::create($OBJ);
                echo 1;
            } else {
                $data->fill($OBJ);
                $data->save();
                echo 1;
            }
        } catch (\Throwable $th) {
            echo 0;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Descargas::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = self::edit($id);
        return self::store($request,$data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $data = self::edit( $request->all()["id"] );
            $data->fill( [ "elim" => 1 ] );
            $data->save();
            /*if(!empty( $data["image"] )) {
                $filename = public_path() . "/{$data["image"]}";
                if ( file_exists( $filename ) )
                    unlink( $filename );
            }
            Descarga::destroy( $request->all()["id"] );*/
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
