<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        // Google user object dari google
        $userFromGoogle = Socialite::driver('google')->user();

        // Cek apakah user sudah ada di database
        $userFromDatabase = User::where('google_id', $userFromGoogle->getId())->first();

        if (!$userFromDatabase) {
            // Buat user baru
            $newUser = User::create([
                'google_id' => $userFromGoogle->getId(),
                'name' => $userFromGoogle->getName(),
                'email' => $userFromGoogle->getEmail(),
                'profile' => $userFromGoogle->getAvatar(),
            ]);

            auth('web')->login($newUser);
            session()->regenerate();

            return redirect()->route('setup-password.form');
        }

        // Jika user ditemukan, login
        auth('web')->login($userFromDatabase);
        session()->regenerate();

        // Jika belum set password, arahkan ke halaman setup
        if (!$userFromDatabase->is_password_set) {
            return redirect()->route('setup-password.form');
        }

        return redirect('/');
    }

    public function setupPasswordForm()
    {
        return view('login.setpass');
    }

    public function setupPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->is_password_set = true;
        $user->save();

        return redirect('/')->with('success', 'Password berhasil diset!');
    }

    public function logout(Request $request)
    {
        auth('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
