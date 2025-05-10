<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LayoutUserController extends Controller
{
    public function dailprog()
    {
        $notif = Notification::where('user_id', Auth::id())->get();

        $belom = Notification::where('user_id', Auth::id())
            ->where('read', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $notiff = $belom->count();

        $daily = Daily::where('user_id', Auth::id())
            ->where('status','progress')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.actv.dailyprog')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $daily
        ]);
    }


    public function daildone()
    {
        $notif = Notification::where('user_id', Auth::id())->get();

        $belom = Notification::where('user_id', Auth::id())
            ->where('read', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $notiff = $belom->count();

        $daily = Daily::where('user_id', Auth::id())
            ->where('status','selesai')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.actv.daildone')->with([
            'user' => Auth::user(),
            'notiff' => $notiff,
            'notifications' => $notif,
            'daily' => $daily
        ]);
    }

}
