<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNoSetPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_password_set && !$request->is('setup-password*')) {
            // Kirim notifikasi lewat session flash
            session()->flash('alert', 'Silakan atur password terlebih dahulu untuk menyelesaikan pendaftaran.');
            return redirect()->route('setup-password.form');
        }

        return $next($request);
    }

}
