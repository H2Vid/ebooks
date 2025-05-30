@extends('layouts.cms')

@section('title', 'Upload eBook')

@section('content')
<div class="bg-gray-100 p-6 rounded-lg shadow-sm">
    <h2 class="text-2xl font-semibold mb-6 text-green-800">Upload eBook</h2>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between w-full md:flex-row gap-8 ">
        {{-- Form Upload --}}
        <form method="POST" action="{{ route('cms.ebooks.store') }}" enctype="multipart/form-data" class="w-[100%] space-y-6" id="uploadForm">
            @csrf

            <div>
                <label for="title" class="block mb-2 font-semibold text-green-700">Judul</label>
                <input
                    id="title"
                    name="title"
                    required
                    type="text"
                    class="w-full rounded border border-green-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                    placeholder="Masukkan judul eBook"
                />
            </div>
            <div>
    <label for="deskripsi" class="block mb-2 font-semibold text-green-700">Deskripsi</label>
    <textarea
        id="deskripsi"
        name="deskripsi"
        rows="4"
        class="w-full rounded border border-green-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
        placeholder="Masukkan deskripsi eBook"
        required
    ></textarea>
</div>


            <div>
                <label for="author" class="block mb-2 font-semibold text-green-700">Penulis</label>
                <input
                    id="author"
                    name="author"
                    required
                    type="text"
                    class="w-full rounded border border-green-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                    placeholder="Masukkan nama penulis"
                />
            </div>

            <div>
                <label for="published_at" class="block mb-2 font-semibold text-green-700">Tanggal Terbit</label>
                <input
                    id="published_at"
                    name="published_at"
                    required
                    type="date"
                    class="w-full rounded border border-green-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                />
            </div>

            <div>
                <label for="cover" class="block mb-2 font-semibold text-green-700">Cover (jpg/png)</label>
                <input
                    id="cover"
                    name="cover"
                    type="file"
                    accept="image/jpeg,image/png"
                    required
                    class="w-full text-green-700"
                />
            </div>

            <div>
                <label for="file" class="block mb-2 font-semibold text-green-700">File PDF</label>
                <input
                    id="file"
                    name="file"
                    type="file"
                    accept="application/pdf"
                    required
                    class="w-full text-green-700"
                />
            </div>

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 transition text-white font-semibold px-6 py-2 rounded shadow"
            >
                Upload
            </button>
        </form>

        {{-- Preview Section --}}
        <div class="w-[50%] p-6 rounded shadow border border-green-200">
            <h3 class="text-xl font-semibold text-green-800 mb-4">Preview eBook</h3>

            <div class="mb-6">
                <p class="font-semibold text-green-700 mb-2">Cover Preview:</p>
                <div class="w-full h-64 bg-green-50 rounded flex items-center justify-center border border-green-200 overflow-hidden">
                    <img id="coverPreview" src="#" alt="Preview Cover" class="hidden max-h-full" />
                    <span id="noCoverText" class="text-green-300">Belum ada gambar dipilih</span>
                </div>
            </div>

            <div>
                <p class="font-semibold text-green-700 mb-2">File PDF yang dipilih:</p>
                <div id="pdfPreview" class="text-green-600 italic border border-green-200 rounded p-4 bg-green-50">
                    Belum ada file PDF dipilih
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const coverInput = document.getElementById('cover');
    const coverPreview = document.getElementById('coverPreview');
    const noCoverText = document.getElementById('noCoverText');

    const pdfInput = document.getElementById('file');
    const pdfPreview = document.getElementById('pdfPreview');

    coverInput.addEventListener('change', function() {
        const file = this.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                coverPreview.setAttribute('src', e.target.result);
                coverPreview.classList.remove('hidden');
                noCoverText.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            coverPreview.setAttribute('src', '#');
            coverPreview.classList.add('hidden');
            noCoverText.classList.remove('hidden');
        }
    });

    pdfInput.addEventListener('change', function() {
        const file = this.files[0];
        if(file) {
            pdfPreview.textContent = file.name;
        } else {
            pdfPreview.textContent = 'Belum ada file PDF dipilih';
        }
    });
</script>
@endpush
