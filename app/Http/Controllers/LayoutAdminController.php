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

  public function activities($userId)
{
    // Mengambil data aktivitas berdasarkan user_id
    $user = User::findOrFail($userId); // Mengambil data user berdasarkan userId
    $activities = Daily::where('user_id', $userId)->get(); // Mengambil seluruh aktivitas dari user

    // Mengambil data notifikasi
    $belom = Notification::where('read', 0)->orderBy('created_at', 'desc')->get();
    $notiff = $belom->count();
    $notif = Notification::orderBy('created_at', 'desc')->get();

    return view('admin.dash.activities', [
        'user' => $user,
        'activities' => $activities,
        'notiff' => $notiff,
        'notifications' => $notif
    ]);
}
public function activityDetail($activityId)
{
    $activity = Daily::with('filedailies')->findOrFail($activityId); // Mengambil detail aktivitas

    return view('admin.dash.activity-detail', compact('activity'));
}



}
