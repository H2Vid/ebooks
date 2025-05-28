<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Ebook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('cms.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/cms/dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal.']);
    }

    public function showRegisterForm()
    {
        return view('cms.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect('/cms/dashboard');
    }

    public function dashboard()
    {
        $ebooks = Ebook::latest()->get();
    return view('cms.dashboard', compact('ebooks'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/cms/login');
    }
}
