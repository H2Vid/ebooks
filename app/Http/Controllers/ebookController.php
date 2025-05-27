<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EbookController extends Controller
{
    private $ebooks = [
        [
            'title' => 'Panduan ASN Digital',
            'slug' => 'panduan-asn-digital',
            'author' => 'BKN RI',
            'cover' => 'ebooks/asn.jpg',
            'upload_date' => '2025-05-20',
            'file' => 'ebooks/tes.pdf',
        ],
    ];

    public function landing()
    {
        return view('ebooks.landing', ['ebooks' => $this->ebooks]);
    }

    public function index()
    {
        return view('ebooks.index', ['ebooks' => $this->ebooks]);
    }

    public function show($slug)
    {
        $ebook = collect($this->ebooks)->firstWhere('slug', $slug);

        abort_unless($ebook, 404);
        return view('ebooks.show', ['ebook' => $ebook]);
    }

}


