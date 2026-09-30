<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Autentikasi berbasis session memakai kolom `users.email` + `users.password`,
 * dengan penolakan eksplisit untuk akun non-aktif (`users.is_active`).
 */
class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $kredensial = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($kredensial, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah, atau akun Anda sedang tidak aktif.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->targetkan($request));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Admin diarahkan ke penjadwalan, user biasa ke modul data (read-only).
     */
    private function targetkan(Request $request): string
    {
        return $request->user()->isAdmin()
            ? route('jadwal.index')
            : route('data.index');
    }
}
