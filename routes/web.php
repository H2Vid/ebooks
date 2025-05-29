<?php

use App\Models\Ebook;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ebookController;
use App\Http\Controllers\EbooksLandingController;

Route::get('/', [EbooksLandingController::class, 'landing']);
Route::get('/ebooks', [EbooksLandingController::class, 'index']);
Route::get('/ebooks/search', [EbooksLandingController::class, 'search'])->name('ebooks.search');
Route::get('/ebooks/{slug}', [EbooksLandingController::class, 'show']);
Route::get('/ebooks/{slug}/read', function ($slug) {
    $ebook = Ebook::get()->first(function ($item) use ($slug) {
        return Str::slug($item->title) === $slug;
    });

    abort_unless($ebook, 404);

    $pdfPath = asset('storage/' . $ebook->file);
    return redirect("/pdfjs/web/viewer.html?file=$pdfPath");
});

