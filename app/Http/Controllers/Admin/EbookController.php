<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Ebook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        'chapters'                                      => 'required|array',
        'chapters.*.title'                              => 'required|string',
        'chapters.*.subchapters'                        => 'required|array',
        'chapters.*.subchapters.*.title'                => 'required|string',
        'chapters.*.subchapters.*.images'               => 'required|array|min:1',
        'chapters.*.subchapters.*.images.*'             => 'image|max:2048', // 2 MB/halaman
    ]);

    foreach ($validated['chapters'] as $chap) {
        $chapter = $ebook->chapters()->create([
            'title' => $chap['title'],
        ]);

        foreach ($chap['subchapters'] as $sub) {
            // upload semua gambar halaman
            $savedImages = [];
            foreach ($sub['images'] as $imgFile) {
                $path = $imgFile->store('ebook_pages', 'public');
                $savedImages[] = $path;
            }
            $chapter->subchapters()->create([
                'title'   => $sub['title'],
                'content' => json_encode($savedImages), // simpan list halaman
            ]);
        }
    }

    return redirect()
            ->route('cms.ebooks.index')
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
            'title'        => 'required|string',
            'description'  => 'required|string',
            'author'       => 'required|string',
            'release_date' => 'required|date',
            'cover'        => 'nullable|image',
            'pdf'          => 'nullable|mimes:pdf',
            'chapters'     => 'required|array',
            'chapters.*.title' => 'required|string',
            'chapters.*.subchapters' => 'required|array',
            'chapters.*.subchapters.*.title' => 'required|string',
        ]);

        // Handle cover
        if ($request->hasFile('cover')) {
            if ($ebook->cover_path && Storage::disk('public')->exists($ebook->cover_path)) {
                Storage::disk('public')->delete($ebook->cover_path);
            }
            $ebook->cover_path = $request->file('cover')->store('covers', 'public');
        }

        // Handle PDF
        if ($request->hasFile('pdf')) {
            if ($ebook->pdf_path && Storage::disk('public')->exists($ebook->pdf_path)) {
                Storage::disk('public')->delete($ebook->pdf_path);
            }
            $ebook->pdf_path = $request->file('pdf')->store('pdfs', 'public');
        }

        // Update data utama
        $ebook->update([
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'author'       => $validated['author'],
            'release_date' => $validated['release_date'],
        ]);

        // Hapus semua chapter dan subchapter lama
        foreach ($ebook->chapters as $chapter) {
            $chapter->subchapters()->delete();
        }
        $ebook->chapters()->delete();

        // Simpan ulang chapter dan subchapter
        $chapters = $request->input('chapters', []);
        foreach ($chapters as $cIdx => $chapterData) {
            $chapter = $ebook->chapters()->create([
                'title' => $chapterData['title'],
            ]);

            if (!empty($chapterData['subchapters'])) {
                foreach ($chapterData['subchapters'] as $sIdx => $subData) {
                    $existingImages = $subData['existing_images'] ?? [];
                    $newFiles = $request->file("chapters.$cIdx.subchapters.$sIdx.images") ?? [];

                    $savedImages = $existingImages;
                    foreach ($newFiles as $file) {
                        $path = $file->store('ebook_pages', 'public');
                        $savedImages[] = $path;
                    }

                    $chapter->subchapters()->create([
                        'title'   => $subData['title'],
                        'content' => json_encode($savedImages),
                    ]);
                }
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
    if ($ebook->pdf_path && Storage::disk('public')->exists($ebook->pdf_path)) {
        \Storage::disk('public')->delete($ebook->pdf_path);
    }

    // Hapus data eBook dari database
    $ebook->delete();

    return redirect()->route('cms.ebooks.index')
                     ->with('success', 'eBook berhasil dihapus');
}


}