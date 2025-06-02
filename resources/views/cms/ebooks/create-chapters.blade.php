@extends('layouts.cms')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-xl">
  <h1 class="text-2xl font-bold mb-6">Tambah Chapter — {{ $ebook->title }}</h1>

  <form id="chapterForm"
        method="POST"
        action="{{ route('cms.ebooks.chapters.store', $ebook->id) }}"
        enctype="multipart/form-data"
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

<script>
  const chaptersContainer = document.getElementById('chapters');
  let chapterCount = 0;

  // Tambah Bab
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

  // Delegasi klik
  chaptersContainer.addEventListener('click', e => {
    // Tambah Subbab
    if (e.target.classList.contains('addSubchapter')) {
      const chapterId = e.target.dataset.chapter;
      const subDiv = document.getElementById(`chapter-${chapterId}-subchapters`);
      const subId = subDiv.childElementCount;
      const previewId = `chapter${chapterId}_sub${subId}_preview`;

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

        <label class="block font-semibold mb-1">Upload Gambar Konten (Multiple)</label>
        <input type="file"
               class="subchapter-images-input border p-2 rounded w-full mb-2"
               multiple accept="image/*"
               data-chapter="${chapterId}"
               data-sub="${subId}"
               data-preview-id="${previewId}">
        <div id="${previewId}" class="flex flex-wrap gap-2"></div>
      `;
      subDiv.appendChild(wrapper);
    }

    // Hapus Bab
    if (e.target.classList.contains('removeChapter')) {
      const chapEl = e.target.closest('.chapter');
      chapEl.remove();
    }

    // Hapus Subbab
    if (e.target.classList.contains('removeSubchapter')) {
      const subEl = e.target.closest('.subchapter');
      subEl.remove();
    }

    // Hapus Gambar Preview
    if (e.target.classList.contains('remove-image')) {
      const container = e.target.closest('.image-preview');
      const hiddenInput = container.querySelector('input[type="hidden"]');
      if (hiddenInput) hiddenInput.remove(); // remove input dummy
      container.remove();
    }
  });

  // Preview Gambar & Simpan dalam form
  document.addEventListener('change', function (e) {
    if (!e.target.classList.contains('subchapter-images-input')) return;

    const input = e.target;
    const previewId = input.dataset.previewId;
    const chapter = input.dataset.chapter;
    const sub = input.dataset.sub;
    const container = document.getElementById(previewId);

    Array.from(input.files).forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = ev => {
        const wrapper = document.createElement('div');
        wrapper.className = 'relative image-preview';

        wrapper.innerHTML = `
          <img src="${ev.target.result}" class="w-24 h-32 object-cover rounded shadow">
          <button type="button"
                  class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-5 h-5 text-xs remove-image">×</button>
        `;

        // Tambahkan file sebagai dummy hidden input agar tetap terkirim
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        const cloneInput = document.createElement('input');
        cloneInput.type = 'file';
        cloneInput.name = `chapters[${chapter}][subchapters][${sub}][images][]`;
        cloneInput.files = dataTransfer.files;
        cloneInput.className = 'hidden';
        wrapper.appendChild(cloneInput);

        container.appendChild(wrapper);
      };
      reader.readAsDataURL(file);
    });

    // Reset input agar bisa upload gambar yang sama lagi jika perlu
    input.value = '';
  });

  // Simpan
  document.getElementById('saveBtn').addEventListener('click', () => {
    if (chapterForm.checkValidity()) chapterForm.submit();
    else chapterForm.reportValidity();
  });
</script>
@endsection
