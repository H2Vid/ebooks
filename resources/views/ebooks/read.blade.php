@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-12">
  <h1 class="text-2xl font-bold mb-4">📖 {{ $ebook['title'] }}</h1>
  <div class="aspect-video w-full border rounded overflow-hidden shadow">
    <iframe src="{{ asset($ebook['file']) }}" type="application/pdf" class="w-full h-[80vh]" frameborder="0"></iframe>
  </div>
  <div class="mt-6">
    <a href="{{ url('/ebooks/' . $ebook['slug']) }}" class="text-blue-600 hover:underline">← Kembali ke Detail</a>
  </div>
</div>
@endsection
