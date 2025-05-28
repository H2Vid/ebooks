<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

Route::prefix('cms')->name('cms.')->group(function () {
    // Hanya boleh di‑akses tamu (belum login)
    Route::middleware('guest')->group(function () {
        Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login',   [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register',[AuthController::class, 'register']);
    });
Route::middleware('admin.auth')->group(function () {
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard',[AuthController::class, 'dashboard'])->name('dashboard');
});
});
