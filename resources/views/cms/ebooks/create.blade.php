@extends('layouts.cms')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-xl">
  <h1 class="text-2xl font-bold mb-6">Upload eBook Baru</h1>

  <form method="POST"
        action="{{ route('cms.ebooks.store') }}"
        enctype="multipart/form-data"
        class="space-y-6" >
    @csrf

    {{-- Cover --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Cover eBook</label>
      <input type="file" name="cover" accept="image/*"
             class="w-full border p-2 rounded-md shadow-sm" required>
    </div>

    {{-- Judul --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Judul</label>
      <input type="text" name="title"
             class="w-full border p-2 rounded-md shadow-sm" required>
    </div>

    {{-- Penulis --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Penulis</label>
      <input type="text" name="author"
             class="w-full border p-2 rounded-md shadow-sm" required>
    </div>

    {{-- Tanggal Terbit --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Tanggal Terbit</label>
      <input type="date" name="release_date"
             class="w-full border p-2 rounded-md shadow-sm" required>
    </div>

    {{-- Deskripsi --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
      <textarea name="description"
                class="w-full border p-2 rounded-md shadow-sm"
                rows="5" required></textarea>
    </div>

    {{-- File PDF --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Upload File PDF</label>
      <input type="file" name="pdf" accept="application/pdf"
             class="w-full border p-2 rounded-md shadow-sm" required>
    </div>

    {{-- Tombol Simpan --}}
    <div class="pt-6">
      <button type="submit"
              class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
        Simpan eBook
      </button>
    </div>
  </form>
</div>

@endsection
