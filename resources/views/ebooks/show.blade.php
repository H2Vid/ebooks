@extends('layouts.app')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-cover bg-center h-96 flex items-center justify-center text-center text-white"
         style="background-image: url('{{ asset('storage/' . $ebook->cover) }}')">
  <div class="absolute inset-0 bg-black opacity-60"></div>
</section>

{{-- Konten --}}
<div class="max-w-6xl mx-auto py-12 px-4 grid grid-cols-1 md:grid-cols-3 gap-8">

  {{-- Detail eBook --}}
  <div class="md:col-span-2">
    <h2 class="text-2xl font-bold">{{ $ebook->title }}</h2>
    <p class="text-gray-600 mt-2">
      Oleh: {{ $ebook->author }} |
      Diunggah: {{ \Carbon\Carbon::parse($ebook->published_at)->translatedFormat('d F Y') }}
    </p>

    <div class="mt-6 flex gap-4">
      <a href="{{ asset('storage/' . $ebook->file) }}" download
         class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
         📥 Unduh eBook
      </a>
      <a href="{{ url('/ebooks/' . Str::slug($ebook->title) . '/read') }}" target="_blank"
         class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
         📖 Baca Sekarang
      </a>
    </div>

    {{-- Deskripsi --}}
    <div class="mt-8 text-gray-700 leading-relaxed">
     @if (!empty($ebook->deskripsi))
  <p>{{ $ebook->deskripsi }}</p>
@else
  <p class="italic text-sm text-gray-500">*Deskripsi eBook belum tersedia*</p>
@endif

    </div>
  </div>

  {{-- Sidebar eBook Terbaru --}}
  <aside>
    <h3 class="text-lg font-semibold mb-4 border-b pb-2">📚 eBook Terbaru</h3>
    <ul class="space-y-3">
      @foreach($latestEbooks as $latest)
        <li>
          <a href="{{ url('/ebooks/' . Str::slug($latest->title)) }}"
             class="text-blue-600 hover:underline text-sm">
             {{ $latest->title }}
          </a>
        </li>
      @endforeach
    </ul>
  </aside>
</div>

@endsection
