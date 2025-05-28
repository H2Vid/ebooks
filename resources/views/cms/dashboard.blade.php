@extends('layouts.cms')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Selamat datang di Dashboard 🎉</h2>

    <p class="text-gray-700 leading-relaxed mb-6">
        Gunakan menu di kiri untuk <strong>meng‑upload</strong>, <strong>mengedit</strong>,
        atau <strong>menghapus</strong> eBook.
    </p>

    <h3 class="text-xl font-semibold mb-2">Daftar eBook</h3>

    @if($ebooks->isEmpty())
        <p class="text-gray-500">Belum ada eBook yang diupload.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
           @foreach($ebooks as $ebook)
    <div class="bg-white p-4 rounded shadow border relative">
        <img src="{{ asset('storage/' . $ebook->cover) }}" class="w-full h-40 object-cover mb-3 rounded" />

        <h4 class="text-lg font-semibold">{{ $ebook->title }}</h4>
        <p class="text-sm text-gray-600">Penulis: {{ $ebook->author }}</p>
        <p class="text-sm text-gray-500 mb-3">Terbit: {{ \Carbon\Carbon::parse($ebook->published_at)->translatedFormat('d F Y') }}</p>

        <div class="flex gap-2">
            <a href="{{ route('cms.ebooks.edit', $ebook->id) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 text-sm rounded">Edit</a>

            <form action="{{ route('cms.ebooks.destroy', $ebook->id) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus eBook ini?')">
                @csrf
                @method('DELETE')
                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded">Hapus</button>
            </form>
        </div>
    </div>
@endforeach

        </div>
    @endif
@endsection
