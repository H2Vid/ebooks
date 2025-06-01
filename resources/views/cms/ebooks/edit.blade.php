@extends('layouts.cms')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-xl">
  <h1 class="text-2xl font-bold mb-6">Edit eBook</h1>

  <form id="ebookForm"
        method="POST"
        action="{{ route('cms.ebooks.update', $ebook->id) }}"
        enctype="multipart/form-data"
        class="space-y-6">
    @csrf
    <input type="hidden" name="_method" value="PATCH" > {{-- ✅ Manual method override --}}

    {{-- Cover --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Cover eBook</label>
      @if($ebook->cover_path)
        <img src="{{ asset('storage/' . $ebook->cover_path) }}" alt="Cover eBook" class="mb-2 w-48 rounded shadow"  required />
      @endif
      <input type="file" name="cover" accept="image/*" class="w-full border p-2 rounded-md shadow-sm">
      <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti cover.</p>
    </div>

    {{-- Judul --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Judul</label>
      <input type="text" name="title" class="w-full border p-2 rounded-md shadow-sm"
             value="{{ old('title', $ebook->title) }}" required>
    </div>

    {{-- Penulis --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Penulis</label>
      <input type="text" name="author" class="w-full border p-2 rounded-md shadow-sm"
             value="{{ old('author', $ebook->author) }}" required>
    </div>

    {{-- Tanggal Terbit --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Tanggal Terbit</label>
      <input type="date" name="release_date" class="w-full border p-2 rounded-md shadow-sm"
             value="{{ $ebook->release_date->format('Y-m-d') }}" required>
    </div>

    {{-- Deskripsi --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
      <div id="quillDescription" class="bg-white border rounded-md p-2 min-h-[120px]"></div>
      <textarea id="description" name="description" class="hidden">{{ old('description', $ebook->description) }}</textarea>
    </div>

    {{-- File PDF --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Upload File PDF</label>
      @if($ebook->pdf_path)
        <p class="mb-2">File saat ini:
          <a href="{{ asset('storage/' . $ebook->pdf_path) }}" target="_blank" class="text-blue-600 underline">Lihat PDF</a>
        </p>
      @endif
      <input type="file" name="pdf" accept="application/pdf" class="w-full border p-2 rounded-md shadow-sm">
      <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file PDF.</p>
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
        Simpan Perubahan
      </button>
    </div>
  </form>
</div>

{{-- Quill & JS --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
  const quillDescription = new Quill('#quillDescription', { theme: 'snow' });
  quillDescription.root.innerHTML = document.getElementById('description').value;

  let chapterCount = 0;
  const chaptersContainer = document.getElementById('chapters');
  const quillSubchapterEditors = new Map();

  function createSubchapterInput(chapterId, subchapterId, data = {}) {
    const editorId = `chapter${chapterId}_subchapter${subchapterId}_editor`;
    const html = `
      <div class="border p-3 rounded-md bg-gray-50 mb-3">
        <label class="block font-semibold mb-1">Judul Subbab</label>
        <input type="text" name="chapters[${chapterId}][subchapters][${subchapterId}][title]"
               class="w-full border p-2 rounded-md mb-2"
               value="${data.title ?? ''}" required>

        <label class="block font-semibold mb-1">Isi Subbab</label>
        <div id="${editorId}" class="quillEditor bg-white border rounded min-h-[120px]"></div>
      </div>
    `;
    const wrapper = document.createElement('div');
    wrapper.innerHTML = html;
    chaptersContainer.querySelector(`#chapter-${chapterId}-subchapters`).appendChild(wrapper);

    setTimeout(() => {
      const quill = new Quill(`#${editorId}`, { theme: 'snow' });
      if(data.content) {
        quill.root.innerHTML = data.content;
      }
      quillSubchapterEditors.set(editorId, quill);
    }, 10);
  }

  function createChapterInput(chapterId, data = {}) {
    const chapterDiv = document.createElement('div');
    chapterDiv.className = 'border border-gray-300 p-4 rounded-md';
    chapterDiv.id = `chapter-${chapterId}`;
    chapterDiv.innerHTML = `
      <label class="block font-semibold mb-1">Judul Bab</label>
      <input type="text" name="chapters[${chapterId}][title]"
             class="w-full border p-2 rounded-md mb-2"
             value="${data.title ?? ''}" required>

      <div class="subchapters space-y-4" id="chapter-${chapterId}-subchapters"></div>
      <button type="button"
              class="addSubchapter px-3 py-1 bg-indigo-500 text-white rounded"
              data-chapter="${chapterId}">
        + Tambah Subbab
      </button>
    `;
    chaptersContainer.appendChild(chapterDiv);

    if(data.subchapters) {
      data.subchapters.forEach((sub, i) => {
        createSubchapterInput(chapterId, i, sub);
      });
    }
  }

  const oldChapters = {!! json_encode(old('chapters', $ebook->chapters ?? [])) !!};

  if(oldChapters.length > 0) {
    oldChapters.forEach((chapter, i) => {
      createChapterInput(i, chapter);
      chapterCount = i + 1;
    });
  }

  document.getElementById('addChapter').addEventListener('click', () => {
    createChapterInput(chapterCount++);
  });

  chaptersContainer.addEventListener('click', (e) => {
    if (e.target.classList.contains('addSubchapter')) {
      const chapterId = e.target.dataset.chapter;
      const subchaptersDiv = document.getElementById(`chapter-${chapterId}-subchapters`);
      const subchapterId = subchaptersDiv.childElementCount;
      createSubchapterInput(chapterId, subchapterId);
    }
  });

  document.getElementById('saveBtn').addEventListener('click', () => {
    document.getElementById('description').value = quillDescription.root.innerHTML;

    quillSubchapterEditors.forEach((quill, id) => {
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
