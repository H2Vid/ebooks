@extends('layouts.cms')

@section('title', 'Edit eBook')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Edit eBook</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('cms.ebooks.update', $ebook->id) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Judul</label>
            <input name="title" value="{{ old('title', $ebook->title) }}" required class="w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block font-medium">Penulis</label>
            <input name="author" value="{{ old('author', $ebook->author) }}" required class="w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block font-medium">Tanggal Terbit</label>
            <input type="date" name="published_at" value="{{ old('published_at', \Carbon\Carbon::parse($ebook->published_at)->format('Y-m-d')) }}" required class="w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block font-medium">Cover Sekarang</label>
            <img src="{{ asset('storage/' . $ebook->cover) }}" class="h-32 rounded mb-2" alt="Cover">
            <input type="file" name="cover" accept="image/*" class="w-full" />
            <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti cover.</p>
        </div>

        <div>
            <label class="block font-medium">File PDF Sekarang</label>
            <a href="{{ asset('storage/' . $ebook->file) }}" target="_blank" class="text-blue-600 underline">Lihat File</a>
            <input type="file" name="file" accept="application/pdf" class="w-full mt-2" />
            <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti file PDF.</p>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
    </form>
@endsection
