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
  const quillDescription = new Quill('#quillDescription', { theme: 'snow' });
  quillDescription.root.innerHTML = document.getElementById('description').value;

  const chaptersContainer = document.getElementById('chapters');
  let chapterCount = 0;

  function addSubchapter(chapterId, subchapterId, data = {}) {
    const containerId = `chapter${chapterId}_subchapter${subchapterId}_images`;
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

      <label class="block font-semibold mb-1">Gambar Halaman</label>
      <div id="${containerId}" class="image-list flex flex-wrap gap-4 mb-2"></div>

      <input type="file"
             name="chapters[${chapterId}][subchapters][${subchapterId}][images][]"
             accept="image/*"
             class="block mb-2"
             multiple
             onchange="handleImagePreview(event, '${containerId}', 'chapters[${chapterId}][subchapters][${subchapterId}][images][]')">
    `;

    document.getElementById(`chapter-${chapterId}-subchapters`).appendChild(wrapper);

    const imageListEl = document.getElementById(containerId);

    function renderImages(imagePaths) {
      imageListEl.innerHTML = '';
      imagePaths.forEach(imgPath => {
        const fullPath = imgPath.startsWith('http') ? imgPath : '{{ asset("storage") }}/' + imgPath;
        const imgWrapper = document.createElement('div');
        imgWrapper.className = 'relative w-24 h-32 border rounded overflow-hidden';

        imgWrapper.innerHTML = `
          <img src="${fullPath}" alt="Image" class="object-cover w-full h-full">
          <button type="button" class="removeImage absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">×</button>
          <input type="hidden" name="chapters[${chapterId}][subchapters][${subchapterId}][existing_images][]" value="${imgPath}">
        `;
        imageListEl.appendChild(imgWrapper);

        imgWrapper.querySelector('.removeImage').addEventListener('click', () => {
          imgWrapper.remove();
        });
      });
    }

    if (data.content) {
      try {
        const images = JSON.parse(data.content);
        if (Array.isArray(images)) {
          renderImages(images);
        }
      } catch (e) {}
    }
  }

  function handleImagePreview(event, containerId, inputName) {
  const files = event.target.files;
  const imageListEl = document.getElementById(containerId);

  [...files].forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      const imgWrapper = document.createElement('div');
      imgWrapper.className = 'relative w-24 h-32 border rounded overflow-hidden';
      imgWrapper.innerHTML = `
        <img src="${e.target.result}" alt="Preview" class="object-cover w-full h-full">
        <button type="button" class="removeImage absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">×</button>
      `;

      // Buat dummy input file tersembunyi agar file tetap dikirim saat submit
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      const cloneInput = document.createElement('input');
      cloneInput.type = 'file';
      cloneInput.name = inputName;
      cloneInput.files = dataTransfer.files;
      cloneInput.className = 'hidden';
      imgWrapper.appendChild(cloneInput);

      imageListEl.appendChild(imgWrapper);

      imgWrapper.querySelector('.removeImage').addEventListener('click', () => {
        imgWrapper.remove();
      });
    };
    reader.readAsDataURL(file);
  });

  // Reset input asli
  event.target.value = '';
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

    if (data.subchapters) {
      data.subchapters.forEach((sub, idx) => addSubchapter(chapterId, idx, sub));
    }
  }

  const oldChapters = {!! json_encode(old('chapters', $ebook->chapters ?? [])) !!};
  if (oldChapters.length) {
    oldChapters.forEach((chap, idx) => addChapter(idx, chap));
    chapterCount = oldChapters.length;
  }

  chaptersContainer.addEventListener('click', e => {
    if (e.target.classList.contains('addSubchapter')) {
      const chapterId = e.target.dataset.chapter;
      const subchapterId = document.querySelectorAll(`#chapter-${chapterId}-subchapters > .subchapter`).length;
      addSubchapter(chapterId, subchapterId);
    }

    if (e.target.classList.contains('removeChapter')) {
      const chapterEl = e.target.closest('.chapter');
      if (chapterEl) chapterEl.remove();
    }

    if (e.target.classList.contains('removeSubchapter')) {
      const subEl = e.target.closest('.subchapter');
      if (subEl) subEl.remove();
    }
  });

  document.getElementById('addChapter').addEventListener('click', () => addChapter(chapterCount++));

  document.getElementById('saveBtn').addEventListener('click', () => {
    document.getElementById('description').value = quillDescription.root.innerHTML;

    if (ebookForm.checkValidity()) {
      ebookForm.submit();
    } else {
      ebookForm.reportValidity();
    }
  });
</script>

@endsection




