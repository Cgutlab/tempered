<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()["is_admin"]) {
            $data = [
                "view"      => "auth.parts.usuarios.index",
                "title"     => "Usuarios",
                "usuarios"   => User::where("id","!=",Auth::user()["id"])->get()
            ];
            return view('auth.distribuidor',compact('data'));
        } else {

        }
    }
    public function datos() {
        $data = [
            "usuario" => Auth::user(),
            "view"  => "auth.parts.usuarios.datos",
            "title" => "Mis datos"
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
        $datosRequest = $request->all();
        //try {
            $ARR_data = [];
            $ARR_data["name"] = $datosRequest["usuarios_name"];
            $ARR_data["username"] = $datosRequest["usuarios_username"];
            $ARR_data["password"] = null;
            $ARR_data["is_admin"] = is_null($datosRequest["usuarios_is_admin"]) ? 0 : $datosRequest["usuarios_is_admin"];
            
            if(is_null($data)) {
                $ARR_data["password"] = Hash::make($datosRequest["usuarios_password"]);
                User::create($ARR_data);
            } else {
                $ARR_data["password"] = $data["password"];
                if(!empty($datosRequest["usuarios_password"]))
                    $ARR_data["password"] = Hash::make($datosRequest["usuarios_password"]);
                $data->fill($ARR_data);
                $data->save();
            }
            return 1;
        /*} catch (\Throwable $th) {
            return 0;
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
        return User::find($id);
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
        return self::store($request,self::edit($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return 1;
    }
}
