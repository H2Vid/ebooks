<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\EbooksLandingController;

/* ── Landing & Listing ── */
Route::get('/',              [EbooksLandingController::class, 'landing'])->name('landing');
Route::get('/ebooks',        [EbooksLandingController::class, 'index'])->name('ebooks.index');

/* ── Search (dideklarasikan sebelum slug) ── */
Route::get('/ebooks/search', [EbooksLandingController::class, 'search'])->name('ebooks.search');

/* ── Detail & Baca ── */
Route::get('/ebooks/{slug}',          [EbooksLandingController::class, 'show'])->name('ebooks.show');
Route::get('/ebooks/{slug}/read',     [EbooksLandingController::class, 'read'])->name('ebooks.read');
