<?php

namespace App\Http\Controllers\PrivateArea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/index';

    /**
     * Check either username or email.
     * @return string
     */
    public function username( )
    {
        return "username";
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('client')->check()) {
                return redirect()->route('client.polizas');
            }
            return $next($request);
        })
        ->except('salir');
        //$this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('index');
        }
    }

    public function login(Request $request) {
        $this->validateLogin($request);
        
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        //dd($this->guard());
        if($this->guard()->validate($this->credentials($request))) {
            if(Auth::guard('client')->attempt([$this->username() => $request->username, 'password' => $request->password]) || Auth::guard('client')->attempt(['nrodni' => $request->username, 'password' => $request->password])) {
                return redirect()->route('client.descargas');
            }  else {
                $this->incrementLoginAttempts($request);
                return back()->withErrors(['mssg' => "Datos incorrectos"]);
            }
        } else {
            // dd('ok');
            $this->incrementLoginAttempts($request);
            return back()->withErrors(['mssg' => "Datos incorrectos"]);
        }
    }
    public function guard()
    {
        return Auth::guard('client');
    }

    public function salir(Request $request) {
        //dd("D");
        Auth::guard('client')->logout();
        return redirect()->route('index');
    }

}