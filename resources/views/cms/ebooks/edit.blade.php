@extends('layouts.cms')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-xl">
  <h1 class="text-2xl font-bold mb-6">Edit eBook</h1>

  <form id="ebookForm"
        method="POST"
        action="{{ route('cms.ebooks.update', $ebook->id) }}"
        enctype="multipart/form-data"
        class="space-y-6"
        novalidate>
    @csrf
    <input type="hidden" name="_method" value="PATCH"> {{-- manual method override --}}

    {{-- Cover --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Cover eBook</label>
      @if($ebook->cover_path)
        <img src="{{ asset('storage/'.$ebook->cover_path) }}"
             alt="Cover eBook"
             class="mb-2 w-48 rounded shadow">
      @endif
      <input type="file" name="cover" accept="image/*"
             class="w-full border p-2 rounded-md shadow-sm">
      <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti cover.</p>
    </div>

    {{-- Judul --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Judul</label>
      <input type="text" name="title"
             class="w-full border p-2 rounded-md shadow-sm"
             value="{{ old('title', $ebook->title) }}" required>
    </div>

    {{-- Penulis --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Penulis</label>
      <input type="text" name="author"
             class="w-full border p-2 rounded-md shadow-sm"
             value="{{ old('author', $ebook->author) }}" required>
    </div>

    {{-- Tanggal Terbit --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Tanggal Terbit</label>
      <input type="date" name="release_date"
             class="w-full border p-2 rounded-md shadow-sm"
             value="{{ $ebook->release_date->format('Y-m-d') }}" required>
    </div>

    {{-- Deskripsi --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
      <div id="quillDescription" class="bg-white border rounded-md p-2 min-h-[120px]"></div>
      <textarea id="description" name="description" class="hidden" required>{{ old('description', $ebook->description) }}</textarea>
    </div>

    {{-- File PDF --}}
    <div>
      <label class="block font-medium text-gray-700 mb-1">Upload File PDF</label>
      @if($ebook->pdf_path)
        <p class="mb-2">File saat ini:
          <a href="{{ asset('storage/'.$ebook->pdf_path) }}" target="_blank" class="text-blue-600 underline">Lihat PDF</a>
        </p>
      @endif
      <input type="file" name="pdf" accept="application/pdf"
             class="w-full border p-2 rounded-md shadow-sm">
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

{{-- Quill --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
  /* --- Inisialisasi editor deskripsi --- */
  const quillDescription = new Quill('#quillDescription', { theme: 'snow' });
  quillDescription.root.innerHTML = document.getElementById('description').value;

  /* --- State --- */
  const chaptersContainer      = document.getElementById('chapters');
  const quillSubchapterEditors = new Map();   // key = editorId, value = Quill instance
  let chapterCount = 0;                       // incremental ID bab

  /* ---------- Helper pembuatan bab & subbab ---------- */
  function addSubchapter(chapterId, subchapterId, data = {}) {
    const editorId = `chapter${chapterId}_subchapter${subchapterId}_editor`;

    const wrapper = document.createElement('div');
    wrapper.className = 'border p-3 rounded-md bg-gray-50 mb-3 subchapter';
    wrapper.innerHTML = `
      <div class="flex justify-between items-center mb-1">
        <label class="font-semibold">Judul Subbab</label>
        <button type="button" class="text-red-500 removeSubchapter">Hapus Subbab</button>
      </div>
      <input type="text"
             name="chapters[${chapterId}][subchapters][${subchapterId}][title]"
             class="w-full border p-2 rounded-md mb-2"
             value="${data.title ?? ''}" required>

      <label class="block font-semibold mb-1">Isi Subbab</label>
      <div id="${editorId}" class="quillEditor bg-white border rounded min-h-[120px]"></div>
    `;
    document.getElementById(`chapter-${chapterId}-subchapters`).appendChild(wrapper);

    setTimeout(() => {
      const quill = new Quill('#' + editorId, { theme: 'snow' });
      if (data.content) quill.root.innerHTML = data.content;
      quillSubchapterEditors.set(editorId, quill);
    }, 10);
  }

  function addChapter(chapterId, data = {}) {
    const chapterDiv = document.createElement('div');
    chapterDiv.className = 'border border-gray-300 p-4 rounded-md chapter';
    chapterDiv.dataset.chapterId = chapterId;
    chapterDiv.id = `chapter-${chapterId}`;

    chapterDiv.innerHTML = `
      <div class="flex justify-between items-center mb-2">
        <label class="block font-semibold">Judul Bab</label>
        <button type="button" class="text-red-600 removeChapter">Hapus Bab</button>
      </div>
      <input type="text"
             name="chapters[${chapterId}][title]"
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

    /* Subbab existing (edit) */
    if (data.subchapters) {
      data.subchapters.forEach((sub, idx) => addSubchapter(chapterId, idx, sub));
    }
  }

  /* ---------- Render data lama (old / model) ---------- */
  const oldChapters = {!! json_encode(old('chapters', $ebook->chapters ?? [])) !!};
  if (oldChapters.length) {
    oldChapters.forEach((chap, idx) => { addChapter(idx, chap); });
    chapterCount = oldChapters.length;
  }

  /* ---------- Event delegation (chaptersContainer) ---------- */
  chaptersContainer.addEventListener('click', e => {
    /* Tambah Subbab */
    if (e.target.classList.contains('addSubchapter')) {
      const chapterId     = e.target.dataset.chapter;
      const subchapterId  = document.querySelectorAll(`#chapter-${chapterId}-subchapters > .subchapter`).length;
      addSubchapter(chapterId, subchapterId);
    }

    /* Hapus Bab */
    if (e.target.classList.contains('removeChapter')) {
      const chapterEl = e.target.closest('.chapter');
      if (chapterEl) {
        /* bersihkan Quill subbab di dalamnya */
        chapterEl.querySelectorAll('.quillEditor').forEach(ed => {
          quillSubchapterEditors.delete(ed.id);
        });
        chapterEl.remove();
      }
    }

    /* Hapus Subbab */
    if (e.target.classList.contains('removeSubchapter')) {
      const subEl = e.target.closest('.subchapter');
      if (subEl) {
        const ed = subEl.querySelector('.quillEditor');
        if (ed) quillSubchapterEditors.delete(ed.id);
        subEl.remove();
      }
    }
  });

  /* ---------- Tambah Bab baru ---------- */
  document.getElementById('addChapter').addEventListener('click', () => addChapter(chapterCount++));

  /* ---------- Submit ---------- */
  document.getElementById('saveBtn').addEventListener('click', () => {
    /* Simpan deskripsi utama */
    document.getElementById('description').value = quillDescription.root.innerHTML;

    /* Bersihkan hidden input lama */
    document.querySelectorAll('input[type="hidden"][name^="chapters"]').forEach(el => el.remove());

    /* Tambah hidden input konten subbab */
    quillSubchapterEditors.forEach((quill, id) => {
      if (!document.getElementById(id)) return;          // editor sudah dihapus
      const m = id.match(/chapter(\d+)_subchapter(\d+)_editor/);
      if (m) {
        const [ , chapId, subId ] = m;
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = `chapters[${chapId}][subchapters][${subId}][content]`;
        inp.value = quill.root.innerHTML;
        ebookForm.appendChild(inp);
      }
    });

    /* Validasi & submit */
    if (ebookForm.checkValidity()) {
      ebookForm.submit();
    } else {
      ebookForm.reportValidity();
    }
  });
</script>
@endsection
