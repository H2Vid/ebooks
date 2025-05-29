<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>eBook Gratis PNS</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

  {{-- NAVBAR TRANSPARAN --}}
   <header id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 ease-in-out bg-transparent">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <a href="/" class="text-2xl font-bold transition-all duration-300" id="logo-text">eBook ASN</a>
      <button id="menu-toggle" class="md:hidden text-white focus:outline-none">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
          <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <nav id="menu" class="hidden md:flex space-x-6">
        <a href="/" class="text-white hover:text-blue-200 transition">Beranda</a>
        <a href="/ebooks" class="text-white hover:text-blue-200 transition">Daftar eBook</a>
      </nav>
    </div>
    <div id="mobile-menu" class="md:hidden px-4 pb-4 hidden bg-white shadow">
      <a href="/" class="block py-2 text-gray-700 hover:text-blue-600">Beranda</a>
      <a href="/ebooks" class="block py-2 text-gray-700 hover:text-blue-600">Daftar eBook</a>
    </div>
  </header>

  {{-- CONTENT --}}
  <main class="min-h-screen">
    @yield('content')
  </main>

  {{-- FOOTER --}}
  <footer class="bg-white border-t mt-12 py-10 text-sm text-gray-600">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left">
      <div>
        <h3 class="font-semibold text-gray-800">Tentang</h3>
        <p class="mt-2 text-gray-600">
          eBook ASN adalah platform berbagi eBook gratis untuk membantu ASN (PNS) meningkatkan kompetensi dan wawasan secara mandiri.
        </p>
      </div>
      <div>
        <h3 class="font-semibold text-gray-800">Navigasi</h3>
        <ul class="mt-2 space-y-1">
          <li><a href="/" class="hover:text-blue-600">Beranda</a></li>
          <li><a href="/ebooks" class="hover:text-blue-600">Daftar eBook</a></li>
        </ul>
      </div>
      <div>
        <h3 class="font-semibold text-gray-800">Informasi</h3>
        <p class="mt-2 text-gray-600">Semua eBook bersifat gratis dan dapat digunakan untuk pembelajaran mandiri ASN.</p>
      </div>
    </div>
    <div class="text-center mt-10 text-xs text-gray-400">
      &copy; {{ date('Y') }} eBook ASN. Hak cipta dilindungi.
    </div>
  </footer>

  {{-- SCROLL SCRIPT --}}
  <script>
    window.addEventListener("scroll", function () {
      const navbar = document.getElementById("navbar");
      const logoText = document.getElementById("logo-text");
      const links = navbar.querySelectorAll("nav a");

      if (window.scrollY > 50) {
        navbar.classList.remove("bg-transparent");
        navbar.classList.add("bg-white", "shadow");
        logoText.classList.remove("text-white");
        logoText.classList.add("text-blue-700");

        links.forEach(link => {
          link.classList.remove("text-white");
          link.classList.add("text-gray-700");
        });
      } else {
        navbar.classList.add("bg-transparent");
        navbar.classList.remove("bg-white", "shadow");
        logoText.classList.add("text-white");
        logoText.classList.remove("text-blue-700");

        links.forEach(link => {
          link.classList.add("text-white");
          link.classList.remove("text-gray-700");
        });
      }
    });
  </script>

</body>
</html>
