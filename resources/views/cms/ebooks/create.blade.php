@extends('layouts.cms')

@section('title', 'Upload eBook')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Upload eBook</h2>
@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

    <form method="POST" action="{{ route('cms.ebooks.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">Judul</label>
            <input name="title" required class="w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block font-medium">Penulis</label>
            <input name="author" required class="w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block font-medium">Tanggal Terbit</label>
            <input type="date" name="published_at" required class="w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block font-medium">Cover (jpg/png)</label>
            <input type="file" name="cover" accept="image/*" required class="w-full" />
        </div>

        <div>
            <label class="block font-medium">File PDF</label>
            <input type="file" name="file" accept="application/pdf" required class="w-full" />
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
    </form>
@endsection
