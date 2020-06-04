<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;

use App\Familia;
use App\Categoria;
use App\Producto;
use App\Productoimage;
class ProductoimageController extends Controller
{
    public $idioma = ["es","en","it"];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $url , $id )
    {
        $producto = Producto::find( $id );
        $images = $producto->images;
        $categoria = $producto->categoria;
        $data = [
            "view"      => "auth.parts.productoimage",
            "title"     => "Familia",
            "familia"   => $categoria->familia,
            "categoria" => $categoria,
            "producto" => $producto,
            "images"    => $images,
            "url"   => route( "familias.index" ),
            "breadcrumb" => '<li class="breadcrumb-item"><a href="' . route('familias.index') . '">Familia</a></li><li class="breadcrumb-item"><a href="' . URL::to('adm/familia/' . $categoria["url"] . '/' . $categoria->familia["id"] ) . '">' . $categoria->familia["name"] . ' - Modelo</a></li><li class="breadcrumb-item"><a href="' . URL::to('adm/familia/' . $categoria->familia["url"] . '/' . $categoria["url"] . '/' . $categoria["id"] ) . '">' . $categoria["name"] . ' - Producto</a></li><li class="breadcrumb-item active" aria-current="page">Imágenes</li>',
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
        //try {
            $OBJ = (new AdmController)->object( $request , $data );
            //dd($OBJ);
            if(is_null($data)) {
                Productoimage::create($OBJ);
                echo 1;
            } else {
                $data->fill($OBJ);
                $data->save();
                echo 1;
            }
        /*} catch (\Throwable $th) {
            echo 0;
        }*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Productoimage::find($id);
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
        $model = new Productoimage;
        
        try {
            (new AdmController)->delete( self::edit( $request->all()[ "id" ] ) , $model->getFillable() );
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
