<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AksesRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next ,$rules)
    {
        if(!Auth::check()){
            return redirect('login');
        }

        $user = Auth::user();
        if($user->role == $rules)
        return $next($request);

        return redirect('login')->with('error','Tidak ada Hak Akses');
    }
}
