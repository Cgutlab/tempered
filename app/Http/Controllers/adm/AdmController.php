<?php

namespace App\Http\Controllers\adm;
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
}
