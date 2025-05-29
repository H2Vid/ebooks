<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class EbooksLandingController extends Controller
{
    public function landing()
    {
        // Ambil 6 eBook terbaru dari database
        $ebooks = Ebook::orderBy('published_at', 'desc')->get();

        return view('ebooks.landing', compact('ebooks'));
    }

    public function index()
    {
        // Daftar semua eBook (optional: pagination)
        $ebooks = Ebook::latest('published_at')->paginate(9);
        return view('ebooks.index', compact('ebooks'));
    }

    public function show($slug)
    {
          $ebook = Ebook::get()->first(function ($item) use ($slug) {
        return Str::slug($item->title) === $slug;
    });
        return view('ebooks.show', compact('ebook'));
    }
}
