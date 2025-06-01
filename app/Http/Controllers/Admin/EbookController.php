<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{

    /* ───────────────── Index & Create ───────────────── */

    public function index()
    {
        $ebooks = Ebook::latest()->paginate(9);
        return view('cms.ebooks.index', compact('ebooks'));
    }

    public function create()
    {
        /* Form UTAMA saja (tanpa bab) */
        return view('cms.ebooks.create');
    }

    /* ───────────────── STORE (utama) ───────────────── */

    public function store(Request $request)
    {
        /* ⤵︎ hanya validasi data utama */
        $validated = $request->validate([
            'title'         => 'required|string',
            'description'   => 'required|string',
            'author'        => 'required|string',
            'release_date'  => 'required|date',
            'cover'         => 'required|image',
            'pdf'           => 'required|mimes:pdf',
        ]);

        /* simpan files */
        $validated['cover_path'] = $request->file('cover')
                                           ->store('covers', 'public');
        $validated['pdf_path']   = $request->file('pdf')
                                           ->store('pdfs', 'public');

        $ebook = Ebook::create($validated);

        /* 🔀 redirect ke konfirmasi */
        return redirect()->route('cms.ebooks.confirm-chapters', $ebook->id);
    }

    /* ───────────────── KONFIRMASI ───────────────── */

    public function confirmAddChapters(Ebook $ebook)
    {
        /* view dengan SweetAlert “Tambah chapter?” */
        return view('cms.ebooks.confirm-chapters', compact('ebook'));
    }

    /* ───────────────── FORM CHAPTER ───────────────── */

    public function createChapters(Ebook $ebook)
    {
        /* tampilkan form bab‑subbab saja */
        return view('cms.ebooks.create-chapters', compact('ebook'));
    }

    /* ───────────────── STORE CHAPTER ───────────────── */

    public function storeChapters(Request $request, Ebook $ebook)
    {
        $validated = $request->validate([
            'chapters'                                   => 'required|array',
            'chapters.*.title'                           => 'required|string',
            'chapters.*.subchapters'                     => 'required|array',
            'chapters.*.subchapters.*.title'             => 'required|string',
            'chapters.*.subchapters.*.content'           => 'required|string',
        ]);

        /* simpan relasi */
        foreach ($validated['chapters'] as $chapterData) {
            $chapter = $ebook->chapters()->create([
                'title' => $chapterData['title'],
            ]);

            foreach ($chapterData['subchapters'] as $subData) {
                $chapter->subchapters()->create([
                    'title'   => $subData['title'],
                    'content' => $subData['content'],
                ]);
            }
        }

        return redirect()->route('cms.ebooks.index')
                         ->with('success', 'eBook & chapter berhasil disimpan.');
    }

    // Form edit eBook
    public function edit(Ebook $ebook)
    {
        $ebook->load('chapters.subchapters');
        return view('cms.ebooks.edit', compact('ebook'));
    }

    // Update eBook
public function update(Request $request, Ebook $ebook)
{
    $validated = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'author' => 'required|string',
        'release_date' => 'required|date',
        'cover' => 'nullable|image',  // boleh kosong kalau gak ganti cover
        'pdf' => 'nullable|mimes:pdf', // boleh kosong kalau gak ganti pdf
        'chapters' => 'required|array',
        'chapters.*.title' => 'required|string',
        'chapters.*.subchapters' => 'required|array',
        'chapters.*.subchapters.*.title' => 'required|string',
        'chapters.*.subchapters.*.content' => 'required|string',
    ]);

    // Jika ada cover baru, simpan dan update path
    if ($request->hasFile('cover')) {
        // Hapus file lama jika ada
        if ($ebook->cover_path && \Storage::disk('public')->exists($ebook->cover_path)) {
            \Storage::disk('public')->delete($ebook->cover_path);
        }
        $coverPath = $request->file('cover')->store('covers', 'public');
        $ebook->cover_path = $coverPath;
    }

    // Jika ada PDF baru, simpan dan update path
    if ($request->hasFile('pdf')) {
        // Hapus file lama jika ada
        if ($ebook->pdf_path && \Storage::disk('public')->exists($ebook->pdf_path)) {
            \Storage::disk('public')->delete($ebook->pdf_path);
        }
        $pdfPath = $request->file('pdf')->store('pdfs', 'public');
        $ebook->pdf_path = $pdfPath;
    }

    // Update data utama eBook
    $ebook->title = $validated['title'];
    $ebook->description = $validated['description'];
    $ebook->author = $validated['author'];
    $ebook->release_date = $validated['release_date'];
    $ebook->save();

    // Update chapters dan subchapters:
    // Untuk kesederhanaan, kita hapus semua chapters lama lalu simpan yang baru.
    $ebook->chapters()->delete();

    foreach ($validated['chapters'] as $chapterData) {
        $chapter = $ebook->chapters()->create([
            'title' => $chapterData['title'],
        ]);

        foreach ($chapterData['subchapters'] as $subchapterData) {
            $chapter->subchapters()->create([
                'title' => $subchapterData['title'],
                'content' => $subchapterData['content'],
            ]);
        }
    }

    return redirect()->route('cms.ebooks.index')->with('success', 'eBook berhasil diperbarui.');
}
public function destroy($id)
{
    $ebook = Ebook::findOrFail($id);

    // Hapus file cover jika ada
    if ($ebook->cover_path && \Storage::disk('public')->exists($ebook->cover_path)) {
        \Storage::disk('public')->delete($ebook->cover_path);
    }

    // Hapus file PDF jika ada
    if ($ebook->pdf_path && \Storage::disk('public')->exists($ebook->pdf_path)) {
        \Storage::disk('public')->delete($ebook->pdf_path);
    }

    // Hapus data eBook dari database
    $ebook->delete();

    return redirect()->route('cms.ebooks.index')
                     ->with('success', 'eBook berhasil dihapus');
}


}
