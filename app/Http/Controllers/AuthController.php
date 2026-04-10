<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('admin_akses') && !session()->has('login_success')) {
            return redirect('/dashboard');
        }

        return view('login-admin');
    }

    public function cekPin(Request $request)
    {
        $request->validate([
            'pin' => 'required'
        ]);

        $pin_rahasia = 'IThelpdesk123';

        if  ($request->pin === $pin_rahasia) {
            session(['admin_akses' => true]);

            return back()->with('login_success', 'Login Berhasil!');
        }

        return back()->with('error', 'PIN Salah! Coba Lagi.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_akses');
        return redirect('/login')->with('success', 'Logout Berhasil!');
    }
}