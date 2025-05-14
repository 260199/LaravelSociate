<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        if(Auth::user()){
            return redirect()->intended('home');
        }
        return view(('login.login'));
    }

    public function proses(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ],[
            'email.required'=> 'email Tidak Booleh Kosong!',
        ]);

        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
            if($user){
                return redirect()->intended('home');
            }
            return redirect()->intended('Login.login');
        }
        return back()->withErrors([
            'email' => 'email Atau Password Anda Salah!!'
            ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    return redirect('/');
    }
}