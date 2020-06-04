<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdmController extends Controller
{
    public function index() {
        $data = [
            "title" => "Administración",
            "view"  => "auth.parts.index"
        ];

        return view('auth.distribuidor',compact('data'));
    }
    
    /** */
    public function logout() {
        Auth::logout();
    	return redirect()->to('/adm');
    }

    public function delete( $data , $fillable ) {
        if( in_array( "elim" , $fillable ) ) {
            $data->fill( [ "elim" => 1 ] );
            $data->save();
        } else {
            if( in_array( "image" , $fillable ) ) {
                if(!empty( $data->image )) {
                    $filename = public_path() . "/{$data->image}";
                    if ( file_exists( $filename ) )
                        unlink( $filename );
                }
            }
            if( in_array( "photo" , $fillable ) ) {
                if(!empty( $data->photo )) {
                    $filename = public_path() . "/{$data->photo}";
                    if ( file_exists( $filename ) )
                        unlink( $filename );
                }
            }
            $data->delete();
        }
    }
    /**
     * Función encargada de construir los objetos a guardar
     * @version 1.0.4
     * @param @type object request $request
     * @param @type object $data
     * @param @type array $merge
     * @date 10/10/2019
     */
    public function object( $request , $data = null , $merge = null ) {
        $datosRequest = $request->all();

        $datosRequest["ATRIBUTOS"] = json_decode( $datosRequest["ATRIBUTOS"] , true );
        $OBJ = [];

        if( !empty( $merge ) ) {
            for( $x = 0 ; $x < count( $merge ) ; $x ++ ) {
                foreach( $datosRequest AS $k => $v ) {
                    if ( strpos( $k , $merge[ $x ] ) !== false ) {
                        $aux = explode( "_" , $k );
                        $aux_key = "";
                        for( $i = 0 ; $i < count( $aux ) ; $i ++ ) {
                            if( is_numeric( $aux[ $i ] ) ) continue;
                            if( !empty( $aux_key ) ) $aux_key .= "_";
                            $aux_key .= $aux[ $i ];
                        }
                        if( !isset( $aux_datos[$aux_key ] ) )
                            $aux_datos[ $aux_key ] = [];
                        if( !is_array( $v ) )
                            $aux_datos[ $aux_key ][] = $v;
                        unset( $datosRequest[$k] );
                    }
                }
            }
            if( isset( $aux_datos ) )
                $datosRequest = array_merge($datosRequest, $aux_datos);
        }

        for( $x = 0 ; $x < count( $datosRequest[ "ATRIBUTOS" ] ) ; $x++ ) {
            $aux = $datosRequest[ "ATRIBUTOS" ][ $x ];
            switch( $aux[ "TIPO" ] ) {
                case "U":
                    foreach( $aux[ "DATA" ][ "especificacion" ] AS $nombre => $tipo) {
                        $E_aux = null;
                        $var = "{$aux[ "DATA" ][ "name" ]}_{$nombre}";
                        if( isset( $aux[ "NAME" ] ) )
                            $var .= "_{$aux[ "NAME" ]}";
                        if( isset( $aux[ "DATA" ][ "idiomas" ] ) ) {
                            if( isset( $aux[ "DATA" ][ "idiomas" ][ $nombre ] ) ) {
                                $E_aux = [];
                                $auxVar = null;
                                for( $y = 0 ; $y < count( $aux[ "DATA" ][ "idiomas" ][ $nombre ] ) ; $y++ ) {
                                    $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = null;
                                    if( empty( $auxVar ) )
                                        $auxVar = $var;
                                    else
                                        $var = $auxVar;
                                    $var .= "_{$aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ]}";
                                    if( isset( $datosRequest[ $var ])) {
                                        if( $tipo == "TP_CHECK" ) {
                                            if( !empty( $datosRequest[ "{$var}_input" ] ) )
                                                $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $datosRequest[ $var ];
                                        } else if( $tipo == "TP_BLOB") {
                                            $path = $tipo == "TP_FILE" ? "files/" : "images/";
                                            $path .= "{$aux[ "DATA" ][ "detalles" ][ $nombre ][ "FOLDER" ]}";
    
                                            $path .= "/{$aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ]}";
                                            if ( !file_exists( $path ) )
                                                mkdir( $path , 0777 , true );
                                            
                                            $file = $request->file( $var );
                                            if( !is_null( $file ) ) {
                                                $imgData = base64_encode(file_get_contents($file));
                                    
                                                $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $imgData;
                                                $OBJ["mime"] = image_type_to_mime_type(exif_imagetype($file));
                                            }
                                        } else if( $tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                            $path = $tipo == "TP_FILE" ? "files/" : "images/";
                                            //dd($nombre);
                                            $path .= "{$aux[ "DATA" ][ "detalles" ][ $nombre ][ "FOLDER" ]}";
    
                                            $path .= "/{$aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ]}";
                                            if ( !file_exists( $path ) )
                                                mkdir( $path , 0777 , true );
                                            
                                            $file = $request->file( $var );
                                            if( !is_null( $file ) ) {
                                                $fileName = null;
                                                if( !empty( $data ) ) {
                                                    if( isset( $aux[ "COLUMN" ] ) && isset( $aux[ "NAME" ] ) ) {
                                                        if( isset( $data[ $aux[ "COLUMN" ] ] ) && isset( $data[ $aux[ "NAME" ] ] ) )
                                                            $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $data[ $aux[ "COLUMN" ] ][ $nombre ][ $data[ $aux[ "NAME" ] ] ];
                                                        else
                                                            $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $data[ $nombre ];
                                                    } else if( isset( $aux[ "COLUMN" ] ) ) {
                                                        if( isset( $data[ $aux[ "COLUMN" ] ] ) ) {
                                                            if( isset( $data[ $aux[ "COLUMN" ] ][ $nombre ] ) )
                                                                $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $data[ $aux[ "COLUMN" ] ][ $nombre ];
                                                        } else
                                                            $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $data[ $nombre ];
                                                    } else {
                                                        if( isset( $data[ $nombre ] ) )
                                                            $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $data[ $nombre ];
                                                    }
                                                    if( isset( $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ][ "n" ] ) )
                                                        $fileName = $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ][ "n" ];
                                                }
                                                if( empty( $fileName ) )
                                                    $fileName = $tipo == "TP_FILE" ? $file->getClientOriginalName() : time() . "_{$nombre}";
                                                $ext = $file->getClientOriginalExtension();
                                                if( strpos( $fileName , "." ) ) {
                                                    list( $nnn , $ext ) = explode( "." , $fileName );
                                                    $fileName = $nnn;
                                                }
                                                $file->move( $path , "{$fileName}.{$ext}" );
                                                $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = [
                                                    "i" => "{$path}/{$fileName}.{$ext}",
                                                    "e" => $ext,
                                                    "n" => $fileName,
                                                    "d" => $tipo == "TP_FILE" ? null : getimagesize( "{$path}/{$fileName}.{$ext}" )
                                                ];
                                            }
                                        } else {
                                            if( isset( $datosRequest[ $var ] ) )
                                                $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $datosRequest[ $var ];
                                            if( isset( $aux[ "DATA" ][ "detalles" ][ $nombre ] ) ) {
                                                if( isset( $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ) ) {
                                                    if( !empty( $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ) )
                                                        $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = str_slug( $OBJ[ $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ] );
                                                }
                                            }
                                        }
                                    } else {
                                        if( $tipo == "TP_FILE" || $tipo == "TP_IMAGE" || $tipo == "TP_BLOB" ) {
                                            if( isset( $aux[ "COLUMN" ] ) ) {
                                                if( isset( $data[ $aux[ "COLUMN" ] ] ) ) {
                                                    if( isset( $data[ $aux[ "COLUMN" ] ][ $nombre ] ) )
                                                        $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $data[ $aux[ "COLUMN" ] ][ $nombre ];
                                                } else
                                                    $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $data[ $nombre ];
                                            } else
                                                $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $data[ $nombre ];
                                        }
            
                                        if( isset( $aux[ "DATA" ][ "detalles" ][ $nombre ] ) ) {
                                            if( isset( $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ) ) {
                                                if( !empty( $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ) )
                                                    $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = str_slug( $OBJ[ $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ][ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] );
                                            }
                                        }
                                    }
                                }// END FOR
                            }
                        } else {// END IF
                            if( isset( $datosRequest[ $var ])) {
                                if( $tipo == "TP_CHECK" ) {
                                    if( !empty( $datosRequest[ "{$var}_input" ] ) )
                                        $E_aux = $datosRequest[ $var ];
                                } else if( $tipo == "TP_BLOB") {
                                    $file = $request->file( $var );
                                    if( !is_null( $file ) ) {
                                        $imgData = base64_encode(file_get_contents($file));
                            
                                        $E_aux = $imgData;
                                        $OBJ["mime"] = image_type_to_mime_type(exif_imagetype($file));
                                    }
                                } else if( $tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                    
                                    $path = $tipo == "TP_FILE" ? "files/" : "images/";
                                    $path .= "{$aux[ "DATA" ][ "detalles" ][ $nombre ][ "FOLDER" ]}";
                                    if ( !file_exists( $path ) )
                                        mkdir( $path , 0777 , true );
                                    
                                    $file = $request->file( $var );
                                    if( !is_null( $file ) ) {
                                        $fileName = null;
                                        if( !empty( $data ) ) {
                                            if( isset( $aux[ "COLUMN" ] ) && isset( $aux[ "NAME" ] ) ) {
                                                if( isset( $data[ $aux[ "COLUMN" ] ] ) && isset( $data[ $aux[ "NAME" ] ] ) )
                                                    $E_aux = $data[ $aux[ "COLUMN" ] ][ $nombre ][ $data[ $aux[ "NAME" ] ] ];
                                                else
                                                    $E_aux = $data[ $nombre ];
                                            } else if( isset( $aux[ "COLUMN" ] ) ) {
                                                if( isset( $data[ $aux[ "COLUMN" ] ] ) ) {
                                                    if( isset( $data[ $aux[ "COLUMN" ] ][ $nombre ] ) )
                                                        $E_aux = $data[ $aux[ "COLUMN" ] ][ $nombre ];
                                                } else
                                                    $E_aux = $data[ $nombre ];
                                            } else {
                                                if( isset( $data[ $nombre ] ) )
                                                    $E_aux = $data[ $nombre ];
                                            }
                                            if( isset( $E_aux[ "n" ] ) )
                                                $fileName = $E_aux[ "n" ];
                                        }
                                        if( empty( $fileName ) )
                                            $fileName = $tipo == "TP_FILE" ? $file->getClientOriginalName() : time() . "_{$nombre}";
                                        $ext = $file->getClientOriginalExtension();
                                        if( strpos( $fileName , "." ) ) {
                                            list( $nnn , $ext ) = explode( "." , $fileName );
                                            $fileName = $nnn;
                                        }
                                        $file->move( $path , "{$fileName}.{$ext}" );
                                        $E_aux = [
                                            "i" => "{$path}/{$fileName}.{$ext}",
                                            "e" => $ext,
                                            "n" => $fileName,
                                            "d" => $tipo == "TP_FILE" ? null : getimagesize( "{$path}/{$fileName}.{$ext}" )
                                        ];
                                    }
                                } else {
                                    if( isset( $datosRequest[ $var ] ) )
                                        $E_aux = $datosRequest[ $var ];
                                    if( isset( $aux[ "DATA" ][ "detalles" ][ $nombre ] ) ) {
                                        if( isset( $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ) ) {
                                            if( !empty( $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ) )
                                                $E_aux = str_slug( $OBJ[ $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ] );
                                        }
                                    }
                                }
                            } else {
                                if( $tipo == "TP_FILE" || $tipo == "TP_IMAGE" || $tipo == "TP_BLOB" ) {
                                    if( isset( $aux[ "COLUMN" ] ) ) {
                                        if( isset( $data[ $aux[ "COLUMN" ] ] ) ) {
                                            if( isset( $data[ $aux[ "COLUMN" ] ][ $nombre ] ) )
                                                $E_aux = $data[ $aux[ "COLUMN" ] ][ $nombre ];
                                        } else
                                            $E_aux = $data[ $nombre ];
                                    } else
                                        $E_aux = $data[ $nombre ];
                                }
    
                                if( isset( $aux[ "DATA" ][ "detalles" ][ $nombre ] ) ) {
                                    if( isset( $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ) ) {
                                        if( !empty( $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ) )
                                            $E_aux = str_slug( $OBJ[ $aux[ "DATA" ][ "detalles" ][ $nombre ][ "CAST" ] ] );
                                    }
                                }
                            }
                        }
                        if( isset( $aux[ "KEY" ] ) ) {
                            if( !isset( $OBJ[ $aux[ "KEY" ] ] ) )
                                $OBJ[ $aux[ "KEY" ] ] = [];
                            
                            $OBJ[ $aux[ "KEY" ] ][ $nombre ] = $E_aux;
                        } else {
                            if( isset( $aux[ "COLUMN" ] ) && isset( $aux[ "NAME" ] ) ) {
                                if( !isset( $OBJ[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ] ) )
                                    $OBJ[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ] = [];
                                $OBJ[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ][ $nombre ] = $E_aux;
                            } else if( isset( $aux[ "COLUMN" ] ) ) {
                                if( !isset( $OBJ[ $aux[ "COLUMN" ] ] ) )
                                    $OBJ[ $aux[ "COLUMN" ] ] = [];
                                $OBJ[ $aux[ "COLUMN" ] ][ $nombre ] = $E_aux;
                            } else
                                $OBJ[ $nombre ] = $E_aux;
                        }
                    }
                    break;
                case "M":
                    if(isset( $aux["BUCLE"] ) ) {
                        //dd($datosRequest);
                        if( !isset( $datosRequest[ $aux[ "BUCLE" ] ] ) )
                            continue;
                        for( $i = 0 ; $i < count( $datosRequest[ $aux[ "BUCLE" ] ] ) ; $i++ ) {
                            $OBJ_AUX = [];
                            foreach( $aux[ "DATA" ][ "especificacion" ] AS $nombre => $tipo ) {
                                $E_aux = null;
                                $var = "{$aux[ "DATA" ][ "name" ]}";

                                if( isset( $aux[ "NAME" ] ) )
                                    $var .= "_{$aux[ "NAME" ]}";

                                if( isset( $aux[ "TAG" ] ) )
                                    $var .= "_{$aux[ "TAG" ]}";

                                if( isset( $aux[ "COLUMN" ] ) &&  isset( $aux[ "NAME" ] ) )
                                    $var .= "_{$aux[ "COLUMN" ]}";
                                $var .= "_{$nombre}";
                                //dd($var);
                                if( isset( $aux[ "DATA" ][ "idiomas" ][ $nombre ] ) ) {
                                    $E_aux = [];
                                    $EE_aux = [];
                                    $auxVar = null;
                                    for( $y = 0 ; $y < count( $aux[ "DATA" ][ "idiomas" ][ $nombre ] ) ; $y++ ) {
                                        if( !empty( $EE_aux ) )                                        
                                            $EE_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = null;
                                        if( empty( $auxVar ) )
                                            $auxVar = $var;
                                        else
                                            $var = $auxVar;
                                        $var .= "_{$aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ]}";
                                        //dd($var);
                                        if( isset( $datosRequest[ $var ] ) ) {
                                            if( $tipo == "TP_CHECK" ) {
                                                if( !empty( $datosRequest[ "{$var}_input" ][ $i ] ) ) {
                                                    //if( isset( $datosRequest[ $var ][ $i ] ) )
                                                        $EE_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $datosRequest[ "{$var}_input" ][ $i ];
                                                }
                                            } else if($tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                                $path = $tipo == "TP_FILE" ? "files/" : "images/";
                                                $path .= "{$aux[ "DATA" ][ "detalles" ][ $nombre ][ "FOLDER" ]}";
                                                
                                                if ( !file_exists( $path ) )
                                                    mkdir($path, 0777, true);
                                                if( isset( $aux["COLUMN"] ) ) {
                                                    if( !empty( $contenido[ "data" ][ $aux[ "COLUMN" ] ] ) ) {
                                                        if( isset( $contenido[ $aux[ "COLUMN" ] ][ $i ] ) )
                                                            $EE_aux = $contenido[ $aux[ "COLUMN" ] ][ $i ][ $nombre ];
                                                    }
                                                }
                                                $fileAux = $request->file( $var );
                                                $file = null;
                                                if( !is_null( $fileAux )) {
                                                    if( isset( $fileAux[$i] ) )
                                                        $file = $fileAux[$i];
                                                }
                                                if( !is_null( $file ) ) {
                                                    $fileName = null;
                                                    if( !empty( $data ) ) {
                                                        if( isset( $aux[ "COLUMN" ] ) ) {
                                                            if( isset( $data[ $aux[ "COLUMN" ] ] ) )
                                                                $EE_aux = $data[ $aux[ "COLUMN" ] ][ $nombre ];
                                                            else
                                                                $EE_aux = $data[ $nombre ];
                                                        } else 
                                                            $EE_aux = $data[ $nombre ];
                                                        $fileName = $EE_aux[ "n" ];
                                                    }
                                                    if( empty( $fileName ) )
                                                        $fileName = $tipo == "TP_FILE" ? $file->getClientOriginalName() : time() . "_{$nombre}_{$x}_{$i}";
                                                    $ext = $file->getClientOriginalExtension();
                                                    if( strpos( $fileName , "." ) ) {
                                                        list( $nnn , $ext ) = explode( "." , $fileName );
                                                        $fileName = $nnn;
                                                    }
                                                    $file->move( $path , "{$fileName}.{$ext}" );
                                                    $EE_aux = [
                                                        "i" => "{$path}/{$fileName}.{$ext}",
                                                        "e" => $ext,
                                                        "n" => $fileName,
                                                        "d" => $tipo == "TP_FILE" ? null : getimagesize( "{$path}/{$fileName}.{$ext}" )
                                                    ];
                                                }
                                                
                                            } else {
                                                if( isset( $datosRequest[ $var ][ $i ] ) )
                                                    $EE_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $datosRequest[ $var ][ $i ];
                                            }
                                        } else {
                                            if( $tipo == "TP_FILE" || $tipo == "TP_IMAGE" ) {
                                                if( !empty( $data ) ) {
                                                    if( isset( $aux[ "COLUMN" ] ) ) {
                                                        if( isset( $data[ $aux[ "COLUMN" ] ] ) ) {
                                                            if( isset( $data[ $aux[ "COLUMN" ] ][ $i ] ) ) {
                                                                if( isset( $data[ $aux[ "COLUMN" ] ][ $i ][ $nombre ] ) )
                                                                    $EE_aux = $data[ $aux[ "COLUMN" ] ][ $i ][ $nombre ];
                                                            }
                                                        }
                                                    }
                                                    if( empty( $EE_aux ) ) {
                                                        if( isset( $data[ $i ] ) ) {
                                                            if( isset( $data[ $i ][ $nombre ] ) )
                                                                $EE_aux = $data[ $i ][ $nombre ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $E_aux = $EE_aux;
                                    //dd($E_aux);
                                } else {
                                    if( isset( $datosRequest[ $var ] ) ) {
                                        if( $tipo == "TP_CHECK" ) {
                                            if( !empty( $datosRequest[ "{$var}_input" ][ $i ] ) ) {
                                                
                                                if( isset( $aux[ "DATA" ][ "idiomas" ] ) )
                                                    $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $datosRequest[ "{$var}_input" ][ $i ];
                                                else
                                                    $E_aux[ $aux[ "DATA" ][ "especificacion" ][ $nombre ] ] = $datosRequest[ "{$var}_input" ][ $i ];
                                            }
                                        } else if($tipo == "TP_FILE" || $tipo == "TP_IMAGE") {
                                            $path = $tipo == "TP_FILE" ? "files/" : "images/";
                                            $path .= "{$aux[ "DATA" ][ "detalles" ][ $nombre ][ "FOLDER" ]}";
                                            
                                            if ( !file_exists( $path ) )
                                                mkdir($path, 0777, true);
                                            if( isset( $aux["COLUMN"] ) ) {
                                                if( !empty( $contenido[ "data" ][ $aux[ "COLUMN" ] ] ) ) {
                                                    if( isset( $contenido[ $aux[ "COLUMN" ] ][ $i ] ) )
                                                        $E_aux = $contenido[ $aux[ "COLUMN" ] ][ $i ][ $nombre ];
                                                }
                                            }
                                            $fileAux = $request->file( $var );
                                            $file = null;
                                            if( !is_null( $fileAux )) {
                                                if( isset( $fileAux[$i] ) )
                                                    $file = $fileAux[$i];
                                            }
                                            if( !is_null( $file ) ) {
                                                $fileName = null;
                                                if( !empty( $data ) ) {
                                                    if( isset( $aux[ "COLUMN" ] ) ) {
                                                        if( isset( $data[ $aux[ "COLUMN" ] ] ) ) {
                                                            if( isset( $data[ $aux[ "COLUMN" ] ] ) ) {
                                                                if( isset( $data[ $aux[ "COLUMN" ] ][ $nombre ] ) )
                                                                    $E_aux = $data[ $aux[ "COLUMN" ] ][ $nombre ];
                                                            }
                                                        } else {
                                                            if( isset( $data[ $nombre ] ) )
                                                                $E_aux = $data[ $nombre ];
                                                            else
                                                                $E_aux = null;
                                                        }
                                                    } else {
                                                        if( isset( $data[ $nombre ] ) )
                                                            $E_aux = $data[ $nombre ];
                                                    }
                                                    if( !empty( $E_aux ) )
                                                        $fileName = $E_aux[ "n" ];
                                                }
                                                if( empty( $fileName ) )
                                                    $fileName = $tipo == "TP_FILE" ? $file->getClientOriginalName() : time() . "_{$nombre}_{$x}_{$i}";
                                                $ext = $file->getClientOriginalExtension();
                                                if( strpos( $fileName , "." ) ) {
                                                    list( $nnn , $ext ) = explode( "." , $fileName );
                                                    $fileName = $nnn;
                                                }
                                                $file->move( $path , "{$fileName}.{$ext}" );
                                                $E_aux = [
                                                    "i" => "{$path}/{$fileName}.{$ext}",
                                                    "e" => $ext,
                                                    "n" => $fileName,
                                                    "d" => $tipo == "TP_FILE" ? null : getimagesize( "{$path}/{$fileName}.{$ext}" )
                                                ];
                                            }
                                            
                                        } else {
                                            if( isset( $datosRequest[ $var ][ $i ] ) ) {
                                                if( isset( $aux[ "DATA" ][ "idiomas" ][ $nombre ] ) )
                                                    $E_aux[ $aux[ "DATA" ][ "idiomas" ][ $nombre ][ $y ] ] = $datosRequest[ $var ][ $i ];
                                                else
                                                    $E_aux = $datosRequest[ $var ][ $i ];
                                            }
                                        }
                                    } else {
                                        if( $tipo == "TP_FILE" || $tipo == "TP_IMAGE" ) {
                                            $E_aux = null;
                                            //dd($data);
                                            if( !empty( $data ) ) {
                                                if( isset( $aux[ "KEY" ] ) ) {
                                                    if( isset( $data[ $aux[ "KEY" ] ] ) ) {
                                                        if( isset( $data[ $aux[ "KEY" ] ][ $i ] ) ) {
                                                            if( isset( $data[ $aux[ "KEY" ] ][ $i ][ $nombre ] ) )
                                                                $E_aux = $data[ $aux[ "KEY" ] ][ $i ][ $nombre ];
                                                        }
                                                    }
                                                }
                                                if( isset( $aux[ "COLUMN" ] ) ) {
                                                    if( isset( $data[ $aux[ "COLUMN" ] ] ) ) {
                                                        if( isset( $data[ $aux[ "COLUMN" ] ][ $i ] ) ) {
                                                            if( isset( $data[ $aux[ "COLUMN" ] ][ $i ][ $nombre ] ) )
                                                                $E_aux = $data[ $aux[ "COLUMN" ] ][ $i ][ $nombre ];
                                                        }
                                                    }
                                                }
                                                if( empty( $E_aux ) ) {
                                                    if( isset( $data[ $i ] ) ) {
                                                        if( isset( $data[ $i ][ $nombre ] ) )
                                                            $E_aux = $data[ $i ][ $nombre ];
                                                    }
                                                }
                                            }
                                            //dd($E_aux);
                                        }
                                    }
                                }
                                //$OBJ_AUX[] = $E_aux;
                                //KEY: "icon" , COLUMN: "icon" , TAG : "icon"
                                /*if( isset( $aux[ "COLUMN" ] ) && isset( $aux[ "NAME" ] ) ) {
                                    if( !isset( $OBJ[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ] ) )
                                        $OBJ_AUX[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ] = [];
                                    $OBJ_AUX[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ] = $E_aux;
                                } else if( isset( $aux[ "COLUMN" ] ) ) {
                                    if( !isset( $OBJ_AUX[ $aux[ "COLUMN" ] ] ) )
                                        $OBJ_AUX[ $aux[ "COLUMN" ] ] = [];
                                        //dd($E_aux);

                                    $OBJ_AUX[ $aux[ "COLUMN" ] ][$nombre] = $E_aux;
                                } else {

                                    //dd($OBJ_AUX);
                                    $OBJ_AUX = $E_aux;
                                }*/
                                $OBJ_AUX[ $nombre ] = $E_aux;
                                //if( $nombre != "icon")
                                //dd($OBJ_AUX);
                            }
                            if( isset( $aux[ "KEY" ] ) ) {
                                if( !isset( $OBJ[ $aux[ "KEY" ] ] ) )
                                    $OBJ[ $aux[ "KEY" ] ] = [];
                                //dd($OBJ_AUX);
                                $OBJ[ $aux[ "KEY" ] ][] = $OBJ_AUX;
                            } else
                                $OBJ[] = $OBJ_AUX;
                        }
                    } else {
                        if( !isset( $OBJ_AUX ) )
                            $OBJ_AUX = [];
                        foreach( $aux[ "DATA" ][ "especificacion" ] AS $nombre => $tipo ) {
                            $var = "{$aux[ "DATA" ][ "name" ]}";
                            $var .= "_{$nombre}";
                            if( isset( $aux[ "NAME" ] ) )
                                $var .= "_{$aux[ "NAME" ]}";
                            if( isset( $aux[ "COLUMN" ] ) )
                                $var .= "_{$aux[ "COLUMN" ]}";
                            if( isset( $datosRequest[ $var ] ) )
                                $OBJ_AUX = $datosRequest[ $var ];
                        }
                        $OBJ[ $aux[ "COLUMN" ] ] = $OBJ_AUX;
                    }
                    break;
                case "A":
                    $OBJ_AUX = null;
                    foreach( $aux[ "DATA" ][ "especificacion" ] AS $nombre => $tipo ) {
                        $var = "{$aux[ "DATA" ][ "name" ]}";
                        if( isset( $aux[ "NAME" ] ) )
                            $var .= "_{$aux[ "NAME" ]}";
                        $var .= "_{$nombre}";
                        if( isset( $aux[ "COLUMN" ] ) )
                            $var .= "_{$aux[ "COLUMN" ]}";
                        //empresa_email_email_suc_rodriguez_email
                        //empresa_email_email_suc_crovara_email
                        
                        if( isset( $datosRequest[ $var ] ) )
                            $OBJ_AUX = $datosRequest[ $var ];
                    }
                    
                    if( isset( $aux[ "COLUMN" ] ) && isset( $aux[ "NAME" ] ) ) {
                        if( !isset( $OBJ[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ] ) )
                            $OBJ[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ] = [];
                        $OBJ[ $aux[ "COLUMN" ] ][ $aux[ "NAME" ] ] = $OBJ_AUX;
                    } else {
                        if( isset( $aux[ "COLUMN" ] ) )
                            $OBJ[ $aux[ "COLUMN" ] ] = $OBJ_AUX;
                    }
                    break;
            }
            
        }

        return $OBJ;
    }
}
