@extends('layouts.app')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-cover bg-center h-96 flex items-center justify-center text-center text-white rounded-b-3xl shadow-lg"
         style="background-image: url('{{ asset('storage/' . $ebook->cover_path) }}')">
  <div class="absolute inset-0 bg-gradient-to-b from-black/70 to-black/50 rounded-b-3xl"></div>

  <div class="relative z-10 max-w-3xl px-6">
    <h1 class="text-4xl md:text-5xl font-extrabold drop-shadow-lg leading-tight">
      {{ $ebook->title }}
    </h1>
    <p class="mt-3 text-lg md:text-xl font-medium text-blue-300 drop-shadow">
      Oleh: {{ $ebook->author }}
    </p>
  </div>
</section>

{{-- Konten utama --}}
<div class="max-w-7xl mx-auto py-16 px-6 md:px-12 grid grid-cols-1 md:grid-cols-3 gap-12">

  {{-- Detail eBook --}}
  <article class="md:col-span-2 bg-white rounded-3xl shadow-lg p-8 border border-blue-100">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
      <div class="text-gray-600 font-semibold">
        <span>Diunggah: </span>
        <time datetime="{{ $ebook->release_date }}">
          {{ \Carbon\Carbon::parse($ebook->release_date)->translatedFormat('d F Y') }}
        </time>
      </div>

      <div class="flex gap-4">
        <a href="{{ asset('storage/' . $ebook->pdf_path) }}" download
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-xl shadow-md transition">
          📥 Unduh eBook
        </a>

        <a href="{{ route('ebooks.read', Str::slug($ebook->title)) }}" target="_blank"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl shadow-md transition">
          📖 Baca Sekarang
        </a>
      </div>
    </div>

    {{-- Deskripsi --}}
    <section class="mt-10 text-gray-700 leading-relaxed prose max-w-none">
      @if (!empty($ebook->deskripsi))
        {!! nl2br(e($ebook->deskripsi)) !!}
      @else
        <p class="italic text-gray-400">*Deskripsi eBook belum tersedia.*</p>
      @endif
    </section>
  </article>

  {{-- Sidebar eBook Terbaru --}}
  <aside class="bg-white rounded-3xl shadow-lg p-6 border border-blue-100">
    <h3 class="text-xl font-bold text-blue-700 mb-6 border-b border-blue-200 pb-2 flex items-center gap-2">
      📚 eBook Terbaru
    </h3>
    <ul class="space-y-4">
      @foreach($latestEbooks as $latest)
        <li>
          <a href="{{ url('/ebooks/' . Str::slug($latest->title)) }}"
             class="block text-blue-600 hover:text-blue-800 font-medium transition">
            {{ $latest->title }}
          </a>
        </li>
      @endforeach
    </ul>
  </aside>

</div>

@endsection
