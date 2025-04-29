<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LayoutAdminController extends Controller
{
    public function daprog(){
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notif = Notification::all()->sortByDesc('created_at');
        $notiff = $belom->count();
        $notclears = Daily::where('status', 'progress')->orderBy('created_at', 'desc')->get();

        return view('admin.dash.daprog')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $notclears
        ]);
    }
    public function dadone(){
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notif = Notification::all()->sortByDesc('created_at');
        $notiff = $belom->count();
        $notclears = Daily::where('status', 'selesai')->orderBy('created_at', 'desc')->get();

        return view('admin.dash.dadone')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $notclears
        ]);
    }
    public function daall(){
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notif = Notification::all()->sortByDesc('created_at');
        $notiff = $belom->count();
        $all = Daily::all();
        $dailyall = $all->sortByDesc('created_at');

        return view('admin.dash.daall')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $dailyall
        ]);
    }

    public function actvprog()
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
        $notif = Notification::orderBy('created_at', 'desc')->get();
        $dailys = Daily::where('user_id', Auth::id())->get();
        $daily = $dailys->where('status' , 'progress');
    
        return view('admin.dash.actvprog')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $daily
        ]);
    }


    public function actvdone()
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
        $notif = Notification::orderBy('created_at', 'desc')->get();
        $dailys = Daily::where('user_id', Auth::id())->get();
        $daily = $dailys->where('status' , 'selesai');
    
        return view('admin.dash.actvdone')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $daily
        ]);
    }
    public function users()
    {
        $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
        $notiff = $belom->count();
        $notif = Notification::orderBy('created_at', 'desc')->get();
        $users = User::all()->sortByDesc('created_at');
        return view('admin.dash.users')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'users' => $users
        ]);
    }


    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:1,2',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->google_id = Str::uuid(); 
        $user->profile = '/default/undraw_profile.svg';
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->is_password_set = true;
        } else {
            $user->password = null;
            $user->is_password_set = false;
        }
    
        $user->save();
    
        return redirect()->route('users')->with('success', 'User berhasil ditambahkan!');
    }
}
