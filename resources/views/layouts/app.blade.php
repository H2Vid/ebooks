<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eBook Gratis PNS</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <a href="/" class="text-xl font-bold text-blue-700">eBook ASN</a>
      <nav>
        <a href="/ebooks" class="text-gray-700 hover:text-blue-600">Daftar eBook</a>
      </nav>
    </div>
  </header>

  <main class="min-h-screen">
    @yield('content')
  </main>

  <footer class="bg-white text-center py-6 border-t mt-12 text-sm text-gray-500">
    &copy; {{ date('Y') }} eBook ASN - Gratis untuk Profesional PNS
  </footer>
</body>
</html>
