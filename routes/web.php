<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ebookController;

Route::get('/', [ebookController::class, 'landing']);
Route::get('/ebooks', [ebookController::class, 'index']);
Route::get('/ebooks/{slug}', [ebookController::class, 'show']);
Route::get('/ebooks/{slug}/read', function ($slug) {
    $ebook = collect([
        [
            'title' => 'Panduan ASN Digital',
            'slug' => 'panduan-asn-digital',
            'file' => 'ebooks/tes.pdf'
        ]
    ])->firstWhere('slug', $slug);

    if (!$ebook) abort(404);

    $pdfPath = asset($ebook['file']);
    return redirect("/pdfjs/web/viewer.html?file=$pdfPath");
});

