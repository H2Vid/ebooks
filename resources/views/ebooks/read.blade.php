<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $ebook->title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.10/dist/typography.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.data('reader', () => ({
            content: `
                <div class="flex flex-col items-center justify-center h-full text-center px-6"
                     style="background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80');
                            background-size: cover;
                            background-position: center;">
                    <div class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg max-w-md">
                        <h1 class="text-3xl font-bold mb-4 text-gray-800">Selamat Membaca!</h1>
                        <p class="text-gray-700">Silakan pilih subbab dari daftar di samping untuk mulai membaca isi eBook.</p>
                    </div>
                </div>`,
            activeSub: '',
            activeSubIndex: -1,
            openIndexes: [],

            subchapters: [
                @foreach ($ebook->chapters as $chapter)
                    @foreach ($chapter->subchapters as $sub)
                        {
                            id: '{{ $sub->id }}',
                            content: @js($sub->content),
                        },
                    @endforeach
                @endforeach
            ],

            isOpen(index) {
                return this.openIndexes.includes(index);
            },
            toggle(index) {
                if (this.isOpen(index)) {
                    this.openIndexes = this.openIndexes.filter(i => i !== index);
                } else {
                    this.openIndexes.push(index);
                }
            },
            loadSub(content, id) {
                this.content = content;
                this.activeSub = id;
                this.activeSubIndex = this.subchapters.findIndex(sub => sub.id === id);
            },
            goNext() {
                if (this.activeSubIndex < this.subchapters.length - 1) {
                    const next = this.subchapters[this.activeSubIndex + 1];
                    this.loadSub(next.content, next.id);
                }
            },
            goPrev() {
                if (this.activeSubIndex > 0) {
                    const prev = this.subchapters[this.activeSubIndex - 1];
                    this.loadSub(prev.content, prev.id);
                }
            }
        }))
      })

      // Navbar scroll effect
      window.addEventListener("scroll", function () {
        const navbar = document.getElementById("navbar");
        const logoText = document.getElementById("logo-text");
        const links = navbar.querySelectorAll("nav a");
        const hamburgerIcon = document.querySelector("#menu-toggle svg");

        if (window.scrollY > 50) {
          navbar.classList.add("bg-white", "shadow");
          logoText.classList.remove("text-white");
          logoText.classList.add("text-blue-700");

          links.forEach(link => {
            link.classList.remove("text-white");
            link.classList.add("text-gray-700");
          });

          if (hamburgerIcon) {
            hamburgerIcon.classList.remove("text-white");
            hamburgerIcon.classList.add("text-gray-700");
          }
        } else {
          navbar.classList.remove("bg-white", "shadow");
          logoText.classList.add("text-white");
          logoText.classList.remove("text-blue-700");

          links.forEach(link => {
            link.classList.add("text-white");
            link.classList.remove("text-gray-700");
          });

          if (hamburgerIcon) {
            hamburgerIcon.classList.add("text-white");
            hamburgerIcon.classList.remove("text-gray-700");
          }
        }
      });

      // Hamburger menu toggle
      document.addEventListener('DOMContentLoaded', () => {
        const menuToggle = document.getElementById("menu-toggle");
        const mobileMenu = document.getElementById("mobile-menu");

        menuToggle.addEventListener("click", () => {
          mobileMenu.classList.toggle("hidden");
        });

        // Close mobile menu on link click
        document.querySelectorAll("#mobile-menu a").forEach(link => {
          link.addEventListener("click", () => {
            mobileMenu.classList.add("hidden");
          });
        });

        // Trigger scroll effect on load
        window.dispatchEvent(new Event("scroll"));
      });
    </script>
</head>

<body class="bg-white text-gray-800">
 <header id="navbar" class=" transition-all duration-300 ease-in-out bg-black">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      {{-- Logo --}}
      <a href="{{ url('/') }}" class="text-2xl font-bold transition-all duration-300 text-white" id="logo-text">eBook ASN</a>

      {{-- Hamburger Button --}}
      <button id="menu-toggle" class="md:hidden focus:outline-none" aria-label="Toggle menu">
        <svg class="w-6 h-6 text-white transition duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      {{-- Desktop Menu --}}
      <nav id="menu" class="hidden md:flex space-x-6">
        <a href="{{ url('/') }}" class="text-white hover:text-blue-400 transition font-medium">Beranda</a>

        @php
          $isLandingPage = request()->is('/');
        @endphp
        <a href="{{ $isLandingPage ? '#listebook' : url('/#listebook') }}" class="text-white hover:text-blue-600 transition">eBook</a>
      </nav>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="md:hidden px-4 pb-4 hidden bg-white shadow">
      <a href="{{ url('/') }}" class="block py-2 text-gray-700 hover:text-blue-600">Beranda</a>
      <a href="{{ $isLandingPage ? '#listebook' : url('/#listebook') }}" class="text-gray-700 hover:text-blue-600 transition">eBook</a>
    </div>
  </header>

<div class="flex h-screen" x-data="reader">
    <!-- Sidebar -->
    <aside class="w-72 overflow-y-auto border-r border-gray-200 bg-gray-50 px-4 py-6">
        <h2 class="text-lg font-bold mb-4">{{ $ebook->title }}</h2>

        <nav class="space-y-2">
            @foreach ($ebook->chapters as $index => $chapter)
                <div class="border-b border-gray-300 pb-2">
                    <button
                        class="w-full flex justify-between items-center text-left font-semibold text-gray-700 hover:bg-gray-200 px-2 py-2 rounded"
                        @click="toggle({{ $index }})"
                    >
                        <span>{{ $chapter->title }}</span>
                        <svg :class="isOpen({{ $index }}) ? 'rotate-90' : ''"
                             class="w-4 h-4 transform transition-transform duration-200 text-gray-500"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div x-show="isOpen({{ $index }})" x-transition>
                        @foreach ($chapter->subchapters as $sub)
                            <a href="#"
                               @click.prevent="loadSub(@js($sub->content), '{{ $sub->id }}')"
                               :class="activeSub === '{{ $sub->id }}' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                               class="block px-4 py-1 text-sm rounded ml-4">
                                {{ $sub->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>
    </aside>

    <!-- Content Area -->
    <!-- Content Area -->
<main class="flex-1 overflow-y-auto p-6 flex flex-col items-center text-2xl">
    <article
      class="prose max-w-none flex-grow bg-white p-10 rounded-lg shadow-lg"
      style="width: 800px; height: 1100px; box-shadow: 0 10px 20px rgba(0,0,0,0.15); overflow-y: auto;"
      x-html="content"
    ></article>

    <!-- Next & Prev Buttons -->
    <div class="mt-4 flex justify-between w-[800px]">
        <button
            class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
            :disabled="activeSubIndex === 0"
            @click="goPrev()"
        >
            &larr; Prev
        </button>

        <button
            class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
            :disabled="activeSubIndex === subchapters.length - 1"
            @click="goNext()"
        >
            Next &rarr;
        </button>
    </div>
</main>

</div>

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
          <li><a href="{{ url('/') }}" class="hover:text-blue-600">Beranda</a></li>
          <li><a href="{{ url('/ebooks') }}" class="hover:text-blue-600">Daftar eBook</a></li>
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
</body>

</html>
