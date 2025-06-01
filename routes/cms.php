<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EbookController;

Route::prefix('cms')->name('cms.')->group(function () {

    // ---------- TAMU ----------
    Route::middleware('guest')->group(function () {
        Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login',   [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register',[AuthController::class, 'register']);
    });

    // ---------- ADMIN ----------
    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard',[AuthController::class, 'dashboard'])->name('dashboard');

        // resource eBook — cukup sekali dan beri nama “ebooks”
       Route::prefix('ebooks')->name('ebooks.')->group(function () {
    Route::get('/', [EbookController::class, 'index'])->name('index');
    Route::get('/create', [EbookController::class, 'create'])->name('create');
    Route::post('/', [EbookController::class, 'store'])->name('store');
    Route::get('/{ebook}/edit', [EbookController::class, 'edit'])->name('edit');
    Route::patch('/{ebook}', [EbookController::class, 'update'])->name('update'); // <-- ini
    Route::delete('/{ebook}', [EbookController::class, 'destroy'])->name('destroy');
});

    });
});
