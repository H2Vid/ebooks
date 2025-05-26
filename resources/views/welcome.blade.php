<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
       @vite('resources/css/app.css')

    </head>
    <body class="font-sans antialiased ">
      @extends('layouts.app')

@section('content')
<section class="bg-blue-50 py-16 text-center">
  <h1 class="text-4xl font-bold">Platform eBook Gratis untuk Profesional PNS</h1>
  <p class="mt-4 text-lg text-gray-700">Unduh eBook bermanfaat dan tingkatkan kompetensi ASN Anda</p>
  <a href="{{ url('/ebooks') }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Lihat Daftar eBook</a>
</section>

<section class="py-12 bg-white max-w-6xl mx-auto">
  <h2 class="text-2xl font-bold mb-6">eBook Terbaru</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach(array_slice($ebooks, 0, 3) as $ebook)
      <div class="border rounded-xl overflow-hidden shadow hover:shadow-md transition">
        <img src="{{ asset($ebook['cover']) }}" class="w-full h-60 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg">{{ $ebook['title'] }}</h3>
          <p class="text-sm text-gray-600">Oleh: {{ $ebook['author'] }}</p>
          <a href="{{ url('/ebooks/' . $ebook['slug']) }}" class="text-blue-600 mt-2 inline-block">Lihat Detail</a>
        </div>
      </div>
    @endforeach
  </div>
</section>
@endsection

    </body>
</html>
