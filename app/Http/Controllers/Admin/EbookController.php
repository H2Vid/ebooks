<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    // Tampilkan semua eBook (index)
    public function index()
    {
        $ebooks = Ebook::latest()->paginate(9);
        return view('cms.ebooks.index', compact('ebooks'));
    }

    // Form tambah eBook
    public function create()
    {
        return view('cms.ebooks.create');
    }

    // Simpan eBook baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'author' => 'required|string',
            'release_date' => 'required|date',
            'cover' => 'required|image',
            'pdf' => 'required|mimes:pdf',
            'chapters' => 'required|array',
            'chapters.*.title' => 'required|string',
            'chapters.*.subchapters' => 'required|array',
            'chapters.*.subchapters.*.title' => 'required|string',
            'chapters.*.subchapters.*.content' => 'required|string',
        ]);

        // Simpan file cover
        $coverPath = $request->file('cover')->store('covers', 'public');

        // Simpan file PDF
        $pdfPath = $request->file('pdf')->store('pdfs', 'public');

        // Simpan data eBook
        $ebook = Ebook::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'author' => $validated['author'],
            'release_date' => $validated['release_date'],
            'cover_path' => $coverPath,
            'pdf_path' => $pdfPath,
        ]);

        // Simpan chapters dan subchapters
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

        return redirect()->route('cms.ebooks.index')->with('success', 'eBook berhasil disimpan.');
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
