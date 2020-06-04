<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Cliente;
class ClienteController extends Controller
{
    public $idioma = ["es","en","it"];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataRequest = $request->all();
        
        if( !isset( $dataRequest["archivo"] ) && !isset( $dataRequest[ "cliente_id" ] ) ) {
            $clientes = Cliente::orderBy('name')->paginate(15);
            $data = [
                "view"      => "auth.parts.cliente",
                "title"     => "Cliente",
                "clientes"   => $clientes
            ];
            return view('auth.distribuidor',compact('data'));
        }
        if( isset( $dataRequest[ "cliente_id" ] ) ) {
            try {
                $cliente = Cliente::find( $dataRequest[ "cliente_id" ] );
                $cliente->fill([
                    "password" => Hash::make( $dataRequest[ "pass" ] ),
                ]);
                $cliente->save();
                return 1;
                /*Mail::to('corzo.pabloariel@gmail.com')->send(new Cambio( $cliente ));
                if (count(Mail::failures()) > 0)
                    return 0;
                else*/
            } catch (\Throwable $th) {
                return 0;
            }
        }
    }

    public function show() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $seccion, $data = null)
    {
        //try {
            $OBJ = (new AdmController)->object( $request , $data );
            //dd($OBJ);
            if(is_null($data)) {
                $OBJ[ "password" ] = Hash::make( $OBJ[ "password" ] );
                Categoria::create($OBJ);
                echo 1;
            } else {
                if( !empty( $OBJ[ "password" ] ) )
                    $OBJ[ "password" ] = Hash::make( $OBJ[ "password" ] );
                
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
        return Cliente::find($id);
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
        return self::store($request,$data["seccion"],$data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $model = new Cliente;
        
        try {
            (new AdmController)->delete( self::edit( $request->all()[ "id" ] ) , $model->getFillable() );
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}