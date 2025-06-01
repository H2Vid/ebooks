@extends('layouts.cms')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-xl">
  <h1 class="text-2xl font-bold mb-6">Upload eBook Baru</h1>
@if ($errors->any())
  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
    <strong>Terjadi kesalahan:</strong>
    <ul class="list-disc list-inside">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

  <form id="ebookForm"
        method="POST"
        action="{{ route('cms.ebooks.store') }}"
        enctype="multipart/form-data"
        class="space-y-6">
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
      <div id="quillDescription" class="bg-white border rounded-md p-2 min-h-[120px]"></div>
      <textarea id="description" name="description" class="hidden"></textarea>
    </div>

    {{-- File PDF --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Upload File PDF</label>
      <input type="file" name="pdf" accept="application/pdf"
             class="w-full border p-2 rounded-md shadow-sm" required>
    </div>

    {{-- Struktur Bab & Subbab --}}
    <div id="chapters" class="space-y-4"></div>

    <button type="button" id="addChapter"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
      + Tambah Bab
    </button>

    {{-- Tombol Simpan --}}
    <div class="pt-6">
      <button type="button" id="saveBtn"
              class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
        Simpan eBook
      </button>
    </div>
  </form>
</div>

{{-- Quill & JS --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
  const quillDescription = new Quill('#quillDescription', {
    theme: 'snow'
  });

  let chapterCount = 0;
  const chaptersContainer = document.getElementById('chapters');
  const quillSubchapterEditors = new Map();

  // Tambah Bab baru
  document.getElementById('addChapter').addEventListener('click', () => {
    const chapterId = chapterCount++;
    const chapterDiv = document.createElement('div');
    chapterDiv.className = 'border border-gray-300 p-4 rounded-md';
    chapterDiv.innerHTML = `
      <label class="block font-semibold mb-1">Judul Bab</label>
      <input type="text" name="chapters[${chapterId}][title]"
             class="w-full border p-2 rounded-md mb-2" required>

      <div class="subchapters space-y-4" id="chapter-${chapterId}-subchapters"></div>
      <button type="button"
              class="addSubchapter px-3 py-1 bg-indigo-500 text-white rounded"
              data-chapter="${chapterId}">
        + Tambah Subbab
      </button>
    `;
    chaptersContainer.appendChild(chapterDiv);
  });

  // Tambah Subbab dalam Bab
  chaptersContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('addSubchapter')) {
      const chapterId = e.target.dataset.chapter;
      const subchaptersDiv = document.getElementById(`chapter-${chapterId}-subchapters`);
      const subchapterCount = subchaptersDiv.childElementCount;
      const subchapterId = subchapterCount;

      const editorId = `chapter${chapterId}_subchapter${subchapterId}_editor`;
      const html = `
        <div class="border p-3 rounded-md bg-gray-50 mb-3">
          <label class="block font-semibold mb-1">Judul Subbab</label>
          <input type="text" name="chapters[${chapterId}][subchapters][${subchapterId}][title]"
                 class="w-full border p-2 rounded-md mb-2" required>

          <label class="block font-semibold mb-1">Isi Subbab</label>
          <div id="${editorId}" class="quillEditor bg-white border rounded min-h-[120px]"></div>
        </div>
      `;
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html;
      subchaptersDiv.appendChild(wrapper);

      // Delay Quill init untuk editor subbab
      setTimeout(() => {
        const quill = new Quill(`#${editorId}`, { theme: 'snow' });
        quillSubchapterEditors.set(editorId, quill);
      }, 10);
    }
  });

  // Submit form
  document.getElementById('saveBtn').addEventListener('click', () => {
    // Simpan deskripsi utama ke textarea tersembunyi
    document.getElementById('description').value = quillDescription.root.innerHTML;

    // Simpan semua isi subbab ke input hidden dengan nama sesuai struktur validasi
    quillSubchapterEditors.forEach((quill, id) => {
      // id format: chapter{chapterId}_subchapter{subchapterId}_editor
      const matches = id.match(/chapter(\d+)_subchapter(\d+)_editor/);
      if (matches) {
        const chapterId = matches[1];
        const subchapterId = matches[2];
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `chapters[${chapterId}][subchapters][${subchapterId}][content]`;
        hiddenInput.value = quill.root.innerHTML;
        document.getElementById('ebookForm').appendChild(hiddenInput);
      }
    });

    document.getElementById('ebookForm').submit();
  });
</script>
@endsection
