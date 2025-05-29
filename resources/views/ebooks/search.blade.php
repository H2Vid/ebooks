@extends('layouts.app')

@section('content')

{{-- Hero Section dengan background dan overlay --}}
<section style="background-image: url('/img/bglandingpage.jpg')" class="relative bg-cover bg-center h-64 flex flex-col justify-center items-center text-center px-4">
    <div class="absolute inset-0 bg-black opacity-60"></div>
    <div class="relative z-10 text-white max-w-4xl">
        <h1 class="text-3xl md:text-5xl font-bold leading-snug">
            Hasil Pencarian eBook
        </h1>
        <p class="mt-2 text-lg">
            Menampilkan hasil pencarian untuk: <span class="underline font-semibold">"{{ $query }}"</span>
        </p>
    </div>
</section>

{{-- Konten hasil pencarian --}}
<div class="max-w-6xl mx-auto py-12 px-4">

  @if ($message)
    <p class="text-gray-600 italic mb-6">{{ $message }}</p>
  @endif

  @if ($ebooks->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      @foreach($ebooks as $ebook)
        <div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">
          <img src="{{ asset('storage/' . $ebook->cover) }}" alt="{{ $ebook->title }}" class="w-full h-60 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-lg">{{ $ebook->title }}</h3>
            <p class="text-sm text-gray-600 mb-2">Oleh: {{ $ebook->author }}</p>
            <a href="{{ url('/ebooks/' . \Illuminate\Support\Str::slug($ebook->title)) }}"
               class="text-blue-600 text-sm hover:underline">
               Lihat Detail
            </a>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <p class="text-center text-gray-500">Tidak ada hasil yang cocok untuk pencarian ini.</p>
  @endif

</div>

@endsection
