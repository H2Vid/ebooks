@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12">
  <img src="{{ asset($ebook['cover']) }}" class="w-full rounded-xl mb-6">
  <h1 class="text-3xl font-bold">{{ $ebook['title'] }}</h1>
  <p class="text-gray-600 mt-2">Oleh: {{ $ebook['author'] }} | Diunggah: {{ \Carbon\Carbon::parse($ebook['upload_date'])->translatedFormat('d F Y') }}</p>
<div class="mt-6 flex gap-4">
  <a href="{{ asset($ebook['file']) }}" download class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">📥 Unduh eBook</a>
  <a href="{{ url('/ebooks/' . $ebook['slug'] . '/read') }}"
   target="_blank"
   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
  📖 Baca Sekarang
</a>

</div>

</div>
@endsection
