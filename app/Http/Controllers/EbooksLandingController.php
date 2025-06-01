<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EbooksLandingController extends Controller
{
    /* ─────────  LANDING  ( '/' )  ───────── */
    public function landing()
    {
        // 6 eBook terbaru (pakai release_date, fallback created_at)
        $ebooks = Ebook::orderByDesc('release_date')
                       ->orderByDesc('created_at')
                       ->take(6)
                       ->get();

        return view('ebooks.landing', compact('ebooks'));
    }

    /* ─────────  LIST SEMUA  ( '/ebooks' )  ───────── */
    public function index()
    {
        $ebooks = Ebook::orderByDesc('release_date')
                       ->orderByDesc('created_at')
                       ->paginate(9);

        return view('ebooks.index', compact('ebooks'));
    }

    /* ─────────  DETAIL  ( '/ebooks/{slug}' )  ───────── */
    public function show(string $slug)
    {
        // Cari langsung di DB menggunakan slug(title)
        $ebook = Ebook::whereRaw("LOWER(REPLACE(title,' ', '-')) = ?", [$slug])
                      ->first();

        // Fallback safety (jika DB tidak case‑match)    ⤵
        if (!$ebook) {
            $ebook = Ebook::get()->first(fn ($e) => Str::slug($e->title) === $slug);
        }

        abort_unless($ebook, 404);

        $latestEbooks = Ebook::latest()->take(5)->get();

        return view('ebooks.show', compact('ebook', 'latestEbooks'));
    }

    /* ─────────  BACA  ( '/ebooks/{slug}/read' )  ───────── */
    public function read(string $slug)
    {
        $ebook = Ebook::whereRaw("LOWER(REPLACE(title,' ', '-')) = ?", [$slug])
                      ->first();

        if (!$ebook) {
            $ebook = Ebook::get()->first(fn ($e) => Str::slug($e->title) === $slug);
        }

        abort_unless($ebook, 404);

        return view('ebooks.read', compact('ebook'));
    }

    /* ─────────  SEARCH  ( '/ebooks/search?q=...' )  ───────── */
    public function search(Request $request)
    {
        $query = trim($request->input('q'));

        if ($query === '') {
            return redirect()->route('ebooks.index');
        }

        $ebooks = Ebook::where('title', 'LIKE', "%{$query}%")->get();
        $count  = $ebooks->count();

        // 0 hasil
        if ($count === 0) {
            return view('ebooks.search', compact('ebooks', 'query'))
                   ->with('message', 'Tidak ditemukan eBook dengan kata kunci tersebut.');
        }

        // 1 hasil → langsung redirect ke detail
        if ($count === 1) {
            $ebook = $ebooks->first();
            return redirect()->route('ebooks.show', Str::slug($ebook->title));
        }

        // >1 hasil → tampilkan daftar
        return view('ebooks.search', compact('ebooks', 'query'));
    }
}
