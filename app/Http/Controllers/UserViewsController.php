<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserViewsController extends Controller
{
    public function index(){
        $users2 = User::all();
        $users = $users2->sortByDesc('created_at');
        return view('user.index')->with([
            'user' => Auth::user(),
            'users' => $users,
        ]);
    }
}
