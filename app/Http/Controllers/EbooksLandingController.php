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
    $ebook = Ebook::all()->first(function ($item) use ($slug) {
        return Str::slug($item->title) === $slug;
    });

    abort_unless($ebook, 404);

    $latestEbooks = Ebook::latest()->take(5)->get();

    return view('ebooks.show', compact('ebook', 'latestEbooks'));
}
public function search(Request $request)
{
    $query = $request->input('q');

    if (!$query) {
        // Kalau query kosong, redirect ke halaman ebook biasa atau landing page
        return redirect('/ebooks');
    }

    // Cari eBook yang judulnya mirip (LIKE)
    $matchedEbooks = Ebook::where('title', 'LIKE', '%' . $query . '%')->get();

    $count = $matchedEbooks->count();

    if ($count === 0) {
        // Tidak ditemukan, bisa kirim ke halaman hasil pencarian dengan pesan kosong
        return view('ebooks.search', [
            'ebooks' => $matchedEbooks,
            'query' => $query,
            'message' => 'Tidak ditemukan eBook dengan kata kunci tersebut.'
        ]);
    } elseif ($count === 1) {
        // Jika cuma 1 hasil, redirect ke halaman detail eBook
        $ebook = $matchedEbooks->first();
        return redirect('/ebooks/' . Str::slug($ebook->title));
    } else {
        // Kalau banyak hasil, tampilkan halaman hasil pencarian daftar eBook
        return view('ebooks.search', [
            'ebooks' => $matchedEbooks,
            'query' => $query,
            'message' => null
        ]);
    }
}

}