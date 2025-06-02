<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <title>{{ $ebook->title }}</title>

    <!-- Alpine & Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.10/dist/typography.min.css" rel="stylesheet" />
    @vite(['resources/css/app.css','resources/js/app.js'])

    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.data('reader', () => ({
          content: `
          <div class="flex flex-col items-center justify-center w-full h-[500px] text-center px-6"
                   style="background-image:url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80');
                          background-size:cover;background-position:center;">
                <div class="bg-white/80 rounded-lg shadow-lg max-w-md">
                  <h1 class="text-3xl font-bold mb-4 text-blue-800">Selamat Membaca!</h1>
                  <p class="text-gray-700">Silakan pilih subbab di samping.</p>
                </div>
              </div>`,
          contentImages: [],
          currentImageIndex: 0,
          activeSub: '',
          activeSubIndex: -1,
          openIndexes: [],

          subchapters: [
            @foreach($ebook->chapters as $chapter)
              @foreach($chapter->subchapters as $sub)
                @php $data = json_decode($sub->content,true); @endphp
                {
                  id: '{{ $sub->id }}',
                  content: {!! json_encode($data ?: [$sub->content]) !!}
                },
              @endforeach
            @endforeach
          ],

          isOpen(i){ return this.openIndexes.includes(i) },
          toggle(i){
            this.isOpen(i)
              ? this.openIndexes=this.openIndexes.filter(x=>x!==i)
              : this.openIndexes.push(i)
          },

          loadSub(c,id){
            this.activeSub = id;
            this.activeSubIndex = this.subchapters.findIndex(s=>s.id===id);

            if(Array.isArray(c)){
              this.contentImages = c;
              this.currentImageIndex = 0;
              this.updateContent();
            }else{
              this.contentImages = [];
              this.content = c;
            }
            this.$nextTick(()=>document.querySelector('article')?.scrollTo({top:0}));
          },

          updateContent(){
            const p = this.contentImages[this.currentImageIndex];
            this.content = `<img src='{{ asset('storage') }}/`+p+`'
                             class='max-w-full h-auto rounded-lg shadow-md'>`;
          },

          goNext(){
            if(this.currentImageIndex < this.contentImages.length-1){
              this.currentImageIndex++;
              this.updateContent();
            }
          },

          goPrev(){
            if(this.currentImageIndex > 0){
              this.currentImageIndex--;
              this.updateContent();
            }
          }
        }));
      });
    </script>
</head>

<body class="bg-gray-100 text-gray-900 font-sans">
  <!-- Header -->
  <header class="bg-blue-900 text-white sticky top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      <a href="{{ url('/') }}" class="text-3xl font-extrabold tracking-tight hover:text-blue-300 transition">eBook ASN</a>
      <nav>
        <a href="{{ url('/#listebook') }}" class="hidden md:inline-block px-5 py-2 rounded-lg hover:bg-white hover:text-blue-900 font-semibold transition">
          eBook
        </a>
      </nav>
    </div>
  </header>

  <div class="flex h-screen overflow-hidden" x-data="reader">
    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-gray-200 shadow-md overflow-y-auto sticky top-[64px] px-6 py-8">
      <h2 class="text-xl font-bold mb-6 text-blue-800 border-b border-blue-200 pb-3">
        {{ $ebook->title }}
      </h2>
      <nav class="space-y-3">
        @foreach($ebook->chapters as $i => $chapter)
          <div class="border-b border-gray-300 pb-3">
            <button
              @click="toggle({{ $i }})"
              class="w-full flex justify-between items-center text-left font-semibold text-gray-700 hover:bg-blue-50 px-3 py-2 rounded-lg transition"
              :class="isOpen({{ $i }}) ? 'bg-blue-100 text-blue-700' : ''"
              aria-expanded="false"
              aria-controls="chapter-{{ $i }}"
            >
              <span>{{ $chapter->title }}</span>
              <svg
                :class="isOpen({{ $i }}) ? 'rotate-90 text-blue-600' : 'text-gray-400'"
                class="w-5 h-5 transition-transform"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true"
              >
                <path d="M9 5l7 7-7 7" />
              </svg>
            </button>

            <div
              x-show="isOpen({{ $i }})"
              x-transition
              class="mt-2 space-y-1"
              id="chapter-{{ $i }}"
            >
              @foreach($chapter->subchapters as $sub)
                <a
                  href="#"
                  @click.prevent="loadSub(subchapters.find(s=>s.id==='{{ $sub->id }}').content,'{{ $sub->id }}')"
                  :class="activeSub==='{{ $sub->id }}' ? 'bg-blue-600 text-white' : 'hover:bg-blue-100 text-gray-800'"
                  class="block px-5 py-2 rounded-md text-sm font-medium transition"
                >
                  {{ $sub->title }}
                </a>
              @endforeach
            </div>
          </div>
        @endforeach
      </nav>
    </aside>

    <!-- Konten -->
    <main
      class="flex-1 flex flex-col items-center overflow-y-auto p-6 bg-gray-50"
      style="min-width: 0;"
      aria-live="polite"
    >
      <article
        class="bg-white p-6 rounded-xl shadow-lg max-w-4xl w-full prose prose-blue overflow-auto"
        style="max-height: 95vh;"
        x-html="content"
      >
      </article>

      <!-- Tombol Navigasi -->
      <div class="mt-6 flex justify-between max-w-4xl w-full gap-4">
        <button
          class="flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
          :disabled="currentImageIndex <= 0"
          @click="goPrev"
          aria-label="Previous page"
        >
          &larr; Prev
        </button>

        <button
          class="flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
          :disabled="currentImageIndex >= contentImages.length - 1"
          @click="goNext"
          aria-label="Next page"
        >
          Next &rarr;
        </button>
      </div>
    </main>
  </div>

  <!-- Footer -->
  <footer class="bg-blue-900 text-white py-4 text-center text-sm select-none">
    &copy; {{ date('Y') }} eBook ASN
  </footer>
</body>
</html>
