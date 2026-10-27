<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function show(): View
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return view('admin.index');
        }

        if (Auth::check()) {
            Auth::logout();
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $credentials['is_admin'] = true;

        if (! Auth::attempt($credentials)) {
            Auth::logout();

            return back()
                ->withErrors(['email' => 'These credentials do not match an admin account.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('admin');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin');
    }
}
