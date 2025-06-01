<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $ebook->title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.10/dist/typography.min.css" rel="stylesheet">

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
            openIndexes: [],

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
            }
        }))
    })
    </script>
</head>

<body class="bg-white text-gray-800">

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
    <main class="flex-1 overflow-y-auto p-6">
        <article class="prose max-w-none" x-html="content"></article>
    </main>
</div>
</body>
</html>
