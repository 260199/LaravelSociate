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
            'username' => 'required',
            'password' => 'required'
        ],[
            'username.required'=> 'username Tidak Booleh Kosong!',
        ]);

        $credentials = $request->only('username','password');

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
            if($user){
                return redirect()->intended('home');
            }
            return redirect()->intended('Login.login');
        }
        return back()->withErrors([
            'username' => 'username Atau Password Anda Salah!!'
            ])->onlyInput('username');
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    return redirect('/');
    }
}
