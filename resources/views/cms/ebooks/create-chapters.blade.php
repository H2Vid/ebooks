@extends('layouts.cms')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-xl">
  <h1 class="text-2xl font-bold mb-6">Tambah Chapter — {{ $ebook->title }}</h1>

  <form id="chapterForm"
        method="POST"
        action="{{ route('cms.ebooks.chapters.store', $ebook->id) }}"
        class="space-y-6" novalidate>
    @csrf

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
        Simpan Chapter
      </button>
    </div>
  </form>
</div>

{{-- Quill --}}
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
  const quillSubchapterEditors = new Map();
  const chaptersContainer = document.getElementById('chapters');
  let chapterCount = 0;

  /* Tambah Bab */
  document.getElementById('addChapter').addEventListener('click', () => {
    const chapterId = chapterCount++;
    const chapterDiv = document.createElement('div');
    chapterDiv.className = 'border border-gray-300 p-4 rounded-md chapter';
    chapterDiv.dataset.chapterId = chapterId;

    chapterDiv.innerHTML = `
      <div class="flex justify-between items-center mb-2">
        <label class="block font-semibold">Judul Bab</label>
        <button type="button" class="text-red-600 removeChapter">Hapus Bab</button>
      </div>
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

  /* Delegasi klik */
  chaptersContainer.addEventListener('click', e => {
    /* Tambah Subbab */
    if (e.target.classList.contains('addSubchapter')) {
      const chapterId = e.target.dataset.chapter;
      const subDiv    = document.getElementById(`chapter-${chapterId}-subchapters`);
      const subId     = subDiv.childElementCount;
      const editorId  = `chapter${chapterId}_sub${subId}_editor`;

      const wrapper = document.createElement('div');
      wrapper.className = 'border p-3 rounded-md bg-gray-50 mb-3 subchapter';
      wrapper.innerHTML = `
        <div class="flex justify-between items-center mb-1">
          <label class="font-semibold">Judul Subbab</label>
          <button type="button" class="text-red-500 removeSubchapter">Hapus Subbab</button>
        </div>
        <input type="text"
               name="chapters[${chapterId}][subchapters][${subId}][title]"
               class="w-full border p-2 rounded-md mb-2" required>

        <label class="block font-semibold mb-1">Isi Subbab</label>
        <div id="${editorId}" class="quillEditor bg-white border rounded min-h-[120px]"></div>
      `;
      subDiv.appendChild(wrapper);

      setTimeout(() => {
        const quill = new Quill('#' + editorId, { theme: 'snow' });
        quillSubchapterEditors.set(editorId, quill);
      }, 10);
    }

    /* Hapus Bab */
    if (e.target.classList.contains('removeChapter')) {
      const chapEl = e.target.closest('.chapter');
      chapEl.querySelectorAll('.quillEditor').forEach(ed => quillSubchapterEditors.delete(ed.id));
      chapEl.remove();
    }

    /* Hapus Subbab */
    if (e.target.classList.contains('removeSubchapter')) {
      const subEl = e.target.closest('.subchapter');
      const ed    = subEl.querySelector('.quillEditor');
      if (ed) quillSubchapterEditors.delete(ed.id);
      subEl.remove();
    }
  });

  /* Submit */
  document.getElementById('saveBtn').addEventListener('click', () => {
    /* Bersihkan hidden lama */
    document.querySelectorAll('input[type="hidden"][name^="chapters"]').forEach(el => el.remove());

    /* Tambahkan konten subbab */
    quillSubchapterEditors.forEach((quill, id) => {
      if (!document.getElementById(id)) return;   // sudah dihapus
      const m = id.match(/chapter(\d+)_sub(\d+)_editor/);
      if (m) {
        const [ , chapId, subId ] = m;
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = `chapters[${chapId}][subchapters][${subId}][content]`;
        inp.value = quill.root.innerHTML;
        chapterForm.appendChild(inp);
      }
    });

    if (chapterForm.checkValidity()) chapterForm.submit();
    else chapterForm.reportValidity();
  });
</script>
@endsection
