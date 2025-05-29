<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Ebook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('cms.login');
    }


public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $email = $request->input('email');
    $password = $request->input('password');

    // Cek kondisi kosong untuk menampilkan notifikasi spesifik
    if (empty($email) && empty($password)) {
        return back()->with('error', 'Silakan masukkan email dan password akun Anda.');
    } elseif (empty($email)) {
        return back()->with('error', 'Silakan masukkan email Anda.');
    } elseif (empty($password)) {
        return back()->with('error', 'Silakan masukkan password Anda.');
    }

    // Validasi format email
    if ($validator->fails()) {
        return back()->with('error', 'Format email tidak valid.')->withInput();
    }

    // Cek apakah user ditemukan
    $user = User::where('email', $email)->first();

    if (!$user) {
        return back()->with('error', 'Email tidak ditemukan atau salah.')->withInput();
    }

    // Cek panjang password
    if (strlen($password) < 8) {
        return back()->with('error', 'Password minimal 8 karakter.')->withInput();
    }

    // Cek password benar atau tidak
    if (!Hash::check($password, $user->password)) {
        return back()->with('error', 'Password salah.')->withInput();
    }

    // Login sukses
    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->intended('/cms/dashboard')->with('success', 'Berhasil login sebagai admin.');
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
            'password' => ['required', 'min:8'],
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
