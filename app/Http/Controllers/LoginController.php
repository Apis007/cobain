<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Pastikan Anda mengimpor model yang benar
use Illuminate\Support\Facades\Auth; // Impor Auth
use Illuminate\Support\Facades\Hash; // Impor Hash

class LoginController extends Controller
{
    public function halaman_login()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            // Simpan username ke session
            session(['username' => $user->username]);
            return redirect()->intended('/pelanggan');
        } else {
            return back()->withErrors(['loginError' => 'Username atau password salah.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Redirect ke halaman login setelah logout
    }
    
}

