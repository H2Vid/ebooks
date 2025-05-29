@extends('layouts.app')

@section('content')
<section style="background-image: url('/img/bglandingpage.jpg')"  class="relative bg-cover bg-center h-screen flex flex-col justify-center items-center text-center px-4">
   <div class="absolute inset-0 bg-black opacity-60"></div>
  <div class="relative z-10">
  {{-- Judul utama --}}
  <h1 class="flex flex-col space-y-4 text-3xl md:text-5xl text-white font-bold max-w-4xl leading-snug">
    <span>
      Platform eBook Gratis
    </span>
    <span>
      untuk Profesional PNS
    </span>
  </h1>
  <p class="mt-4 text-lg text-gray-200 max-w-xl">
    Unduh eBook bermanfaat dan tingkatkan kompetensi ASN Anda
  </p>
  <form action="{{ route('ebooks.search') }}" method="GET" class="mt-8 w-full ">
  <div class="flex items-center border border-gray-300 rounded-full px-4 py-2 bg-white shadow-sm focus-within:ring-2 focus-within:ring-blue-500">
    <input type="text" name="q" placeholder="Cari eBook..." class="flex-1 outline-none text-gray-700 bg-transparent" required />
    <button type="submit" class="text-gray-500 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16z" />
      </svg>
    </button>
  </div>
</form>

  </div>

</section>

<section id="listebook" class="py-16 bg-gray-50">
  <div class="max-w-6xl mx-auto px-4">
    <h2 class="text-2xl md:text-3xl font-bold mb-10 border-b pb-4 text-center">Sumber belajar untuk semua!</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      @foreach($ebooks as $ebook)
        <div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">
          <img src="{{ asset('storage/' . $ebook->cover) }}" alt="{{ $ebook->title }}" class="w-full h-60 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-lg">{{ $ebook->title }}</h3>
            <p class="text-sm text-gray-600 mb-2">Oleh: {{ $ebook->author }}</p>

            <a href="{{ url('/ebooks/' . Str::slug($ebook->title)) }}"
               class="text-blue-600 text-sm hover:underline">
               Lihat Detail
            </a>

          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection
