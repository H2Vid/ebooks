<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $ebook->title }}</title>

    <!-- Alpine & Tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.10/dist/typography.min.css" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])

    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.data('reader', () => ({
            /* landing page “selamat membaca” */
            content: `
              <div class="flex flex-col items-center justify-center w-full h-full text-center px-6"
                   style="background-image:url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80');
                          background-size:cover;background-position:center;">
                <div class="bg-white/80 rounded-lg shadow-lg max-w-md">
                  <h1 class="text-3xl font-bold mb-4 text-blue-800">Selamat Membaca!</h1>
                  <p class="text-gray-700">Silakan pilih subbab di samping.</p>
                </div>
              </div>`,

            /* state */
            contentImages: [],
            currentImageIndex: 0,
            activeSub: '',
            activeSubIndex: -1,
            openIndexes: [],

            /* kumpulan subbab */
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

            /* accordion */
            isOpen(i){ return this.openIndexes.includes(i) },
            toggle(i){ this.isOpen(i)
                        ? this.openIndexes=this.openIndexes.filter(x=>x!==i)
                        : this.openIndexes.push(i) },

            /* muat subbab */
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

            /* tampil 1 gambar */
            updateContent(){
              const p = this.contentImages[this.currentImageIndex];
              this.content = `<img src='{{ asset('storage') }}/`+p+`'
                               class='w-[900px] h-full  overflow-auto rounded'>`;
            },

            /* navigasi gambar */
            goNext(){ if(this.currentImageIndex < this.contentImages.length-1){
                        this.currentImageIndex++; this.updateContent(); } },
            goPrev(){ if(this.currentImageIndex > 0){
                        this.currentImageIndex--; this.updateContent(); } }
        }));
      });
    </script>
</head>

<body class="bg-gray-100 text-gray-800">
  <!-- Header -->
  <header class="bg-blue-800 text-white sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">
      <a href="{{ url('/') }}" class="text-2xl font-bold">eBook ASN</a>
      <a href="{{ url('/#listebook') }}" class="hidden md:inline-block px-4 py-1 rounded hover:bg-white hover:text-blue-800">eBook</a>
    </div>
  </header>

  <div class="flex h-screen" x-data="reader">
    <!-- Sidebar -->
    <aside class="w-72 overflow-y-auto border-r bg-white px-4 py-6">
      <h2 class="text-lg font-bold mb-4 text-blue-800">{{ $ebook->title }}</h2>
      <nav class="space-y-2">
        @foreach($ebook->chapters as $i => $chapter)
          <div class="border-b border-gray-300 pb-2">
            <button @click="toggle({{ $i }})"
                    class="w-full flex justify-between items-center text-left font-semibold text-gray-700 hover:bg-blue-50 px-2 py-2 rounded">
              <span>{{ $chapter->title }}</span>
              <svg :class="isOpen({{ $i }}) ? 'rotate-90' : ''"
                   class="w-4 h-4 text-gray-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
              </svg>
            </button>

            <div x-show="isOpen({{ $i }})" x-transition>
              @foreach($chapter->subchapters as $sub)
                <a href="#"
                   @click.prevent="loadSub(subchapters.find(s=>s.id==='{{ $sub->id }}').content,'{{ $sub->id }}')"
                   :class="activeSub==='{{ $sub->id }}' ? 'bg-blue-600 text-white' : 'hover:bg-blue-50'"
                   class="block px-4 py-1 text-sm rounded ml-4">
                    {{ $sub->title }}
                </a>
              @endforeach
            </div>
          </div>
        @endforeach
      </nav>
    </aside>

    <!-- Konten -->
    <main class="flex-1 flex flex-col items-center overflow-y-auto p-4">
      <!-- Bingkai abu gelap & padding 2 -->
      <article class="bg-gray-200 p-2 rounded-lg shadow-2xl flex items-center justify-center"
               style="width:1000px;height:1500px;"
               x-html="content">
      </article>

      <!-- Tombol Navigasi -->
      <div class="mt-4 flex justify-between w-[900px]">
        <button class="px-4 py-2 bg-blue-300 text-blue-900 rounded disabled:opacity-50"
                :disabled="currentImageIndex<=0"
                @click="goPrev">&larr; Prev</button>

        <button class="px-4 py-2 bg-blue-300 text-blue-900 rounded disabled:opacity-50"
                :disabled="currentImageIndex>=contentImages.length-1"
                @click="goNext">Next &rarr;</button>
      </div>
    </main>
  </div>

  <!-- Footer -->
  <footer class="bg-blue-800 text-white py-4 text-center text-sm">
      &copy; {{ date('Y') }} eBook ASN
  </footer>
</body>
</html>
