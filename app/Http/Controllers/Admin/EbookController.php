<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ebook;

class EbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('cms.ebooks.create');
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $data = $request->validate([
        'title'        => 'required|string|max:255',
        'author'       => 'required|string|max:255',
        'published_at' => 'required|date',
        'cover'        => 'required|image|mimes:jpg,jpeg,png',
        'file'         => 'required|mimes:pdf',
    ]);

    $coverPath = $request->file('cover')->store('covers', 'public');
    $filePath  = $request->file('file')->store('pdfs', 'public');

    Ebook::create([
        'title'        => $data['title'],
        'author'       => $data['author'],
        'published_at' => $data['published_at'],
        'cover'        => $coverPath,
        'file'         => $filePath,
    ]);
return redirect()->route('cms.ebooks.create')->with('success', 'eBook berhasil diunggah.');

}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
