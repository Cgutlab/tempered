<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;

use App\Familia;
use App\Categoria;
use App\Producto;
class ProductoController extends Controller
{
    public $idioma = ["es","en","it"];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destacados( Request $request ) {
        $dataRequest = $request->all();
        //dd($dataRequest);
        if( empty( $dataRequest ) ) {
            $data = [
                "view"      => "auth.parts.destacados",
                "title"     => "Productos destacados",
                "productos" => Producto::where( "elim" , 0 )->orderBy( "categoria_id")->orderBy( "order" )->paginate( 50 ),
            ];
            
            return view('auth.distribuidor',compact('data'));
        }
        for( $i = 0 ; $i < count( $dataRequest[ "ids" ] ) ; $i++ ) {
            $data = self::edit( $dataRequest[ "ids" ][ $i ] );
            if( isset( $dataRequest[ "destacado" ] ) ) {
                if( in_array( $dataRequest[ "ids" ][ $i ] , $dataRequest[ "destacado" ] ) )
                    $data->fill( [ "is_destacado" => 1 ] );
                else
                    $data->fill( [ "is_destacado" => 0 ] );
            } else
                $data->fill( [ "is_destacado" => 0 ] );
            $data->save();
        }
        return back();
    }
    public function index( $url , $url_f , $id )
    {
        $categoria = Categoria::find( $id );
        $productos = $categoria->productos()->orderBy( 'order' )->get();
        $data = [
            "view"      => "auth.parts.producto",
            "title"     => "Familia",
            "familia"   => $categoria->familia,
            "categoria" => $categoria,
            "productos" => $productos,
            "categorias" => Categoria::where( "elim" , 0 )->pluck( "name" , "id" ),
            "url" => route( 'familias.index' ),
            "breadcrumb" => '<li class="breadcrumb-item"><a href="' . route('familias.index') . '">Familia</a></li><li class="breadcrumb-item"><a href="' . URL::to('adm/familia/' . $categoria->familia["url"] . '/' . $categoria->familia["id"] ) . '">' . $categoria->familia["name"] . ' - Modelo</a></li><li class="breadcrumb-item active" aria-current="page">' .  $categoria["name"] . ' - Productos</li>',
        ];
        
        return view('auth.distribuidor',compact('data'));
    }

    public function all() {
        $data = [
            "view"      => "auth.parts.productos",
            "title"     => "Productos",
            "categoria" => null,
            "familia" => null,
            "categorias" => Categoria::where( "elim" , 0 )->pluck( "name" , "id" ),
            "productos" => Producto::where( "elim" , 0 )->orderBy( "categoria_id")->orderBy( "order" )->paginate( 15 ),
        ];
        
        return view('auth.distribuidor',compact('data'));
    }

    public function deleteDoc( $id ) {
        $data = self::edit( $id );
        $data->fill( [ "file" => null ] );
        $data->save();

        return 1;
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
                Producto::create($OBJ);
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
        return Producto::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , $id)
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
        $model = new Producto;
        
        try {
            (new AdmController)->delete( self::edit( $request->all()[ "id" ] ) , $model->getFillable() );
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
