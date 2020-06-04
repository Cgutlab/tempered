<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Familia;
use App\Categoria;
class CategoriaController extends Controller
{
    public $idioma = ["es","en","it"];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $url , $id )
    {
        $familia = Familia::find( $id );
        $categorias = $familia->categorias()->where("elim",0)->orderBy( 'order' )->get();
        $data = [
            "view"      => "auth.parts.categoria",
            "title"     => "Familia",
            "familia"   => $familia,
            "categorias" => $categorias,
            "url" => route('familias.index'),
            "breadcrumb" => '<li class="breadcrumb-item"><a href="' . route('familias.index') . '">Familias</a></li><li class="breadcrumb-item active" aria-current="page">' . $familia["name"] . ' - Modelos</li>',
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
                Categoria::create($OBJ);
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
        return Categoria::find($id);
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
        $model = new Categoria;
        
        try {
            (new AdmController)->delete( self::edit( $request->all()[ "id" ] ) , $model->getFillable() );
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
