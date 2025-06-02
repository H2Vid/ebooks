@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')

<section id="listebook" class="py-20 bg-gradient-to-br from-blue-50 via-white to-blue-100">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-4xl font-extrabold text-center text-blue-800 mb-12 drop-shadow-sm">
      Temukan eBook Terbaik untuk ASN 🌟
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
      @forelse ($ebooks as $ebook)
        <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1 duration-300 border border-blue-100 overflow-hidden flex flex-col">
          <div class="relative">
            <img src="{{ asset('storage/' . $ebook->cover_path) }}"
                 alt="{{ $ebook->title }}"
                 class="w-full h-72 object-cover rounded-t-2xl">
            <div class="absolute top-2 right-2 bg-white/80 backdrop-blur-sm px-2 py-1 rounded text-xs text-blue-600 font-semibold shadow">
              eBook
            </div>
          </div>

          <div class="p-5 flex flex-col flex-grow">
            <h3 class="text-lg font-bold text-gray-800 leading-snug hover:text-blue-600 transition">
              {{ $ebook->title }}
            </h3>
            <p class="text-sm text-gray-600 mt-1 mb-3">Oleh: <span class="font-medium">{{ $ebook->author }}</span></p>

            {{-- Rating dummy --}}
            <div class="flex items-center text-yellow-400 text-sm mb-4">
              @for ($i = 0; $i < 5; $i++)
                <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $i < 4 ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l2.286 7.012h7.374c.969 0 1.371 1.24.588 1.81l-5.964 4.34 2.286 7.011c.3.921-.755 1.688-1.539 1.118L12 18.902l-5.982 4.316c-.783.57-1.838-.197-1.539-1.118l2.286-7.011-5.964-4.34c-.783-.57-.38-1.81.588-1.81h7.374l2.286-7.012z"/>
                </svg>
              @endfor
            </div>

            <a href="{{ route('ebooks.show', Str::slug($ebook->title)) }}"
               class="mt-auto inline-block text-center text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl transition duration-300">
              📖 Baca Detail
            </a>
          </div>
        </div>
      @empty
        <p class="col-span-full text-center text-gray-500">📭 Belum ada eBook tersedia saat ini.</p>
      @endforelse
    </div>
  </div>
</section>

@endsection
