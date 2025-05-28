@extends('layouts.cms')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Selamat datang di Dashboard 🎉</h2>

    <p class="text-gray-700 leading-relaxed mb-6">
        Gunakan menu di kiri untuk <strong>meng‑upload</strong>, <strong>mengedit</strong>,
        atau <strong>menghapus</strong> eBook.
    </p>

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-semibold">Daftar eBook</h3>
        <button id="toggleLayoutBtn" class="p-2 rounded border hover:bg-blue-100" title="Ganti Tampilan">
            <!-- Default icon (list) -->
            <svg id="layoutIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    @if($ebooks->isEmpty())
        <p class="text-gray-500">Belum ada eBook yang diupload.</p>
    @else
        {{-- Grid View --}}
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ebooks as $ebook)
                <div class="bg-white p-4 rounded shadow border relative">
                    <img src="{{ asset('storage/' . $ebook->cover) }}" class="w-full h-40 object-cover mb-3 rounded" />
                    <h4 class="text-lg font-semibold">{{ $ebook->title }}</h4>
                    <p class="text-sm text-gray-600">Penulis: {{ $ebook->author }}</p>
                    <p class="text-sm text-gray-500 mb-3">
                        Terbit: {{ \Carbon\Carbon::parse($ebook->published_at)->translatedFormat('d F Y') }}
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ route('cms.ebooks.edit', $ebook->id) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 text-sm rounded">Edit</a>
                        <form action="{{ route('cms.ebooks.destroy', $ebook->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus eBook ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Table View --}}
        <div id="tableView" class="hidden overflow-x-auto">
            <table class="w-full bg-white border border-gray-200 rounded">
                <thead class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Cover</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Penulis</th>
                        <th class="p-3">Tanggal Terbit</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @foreach($ebooks as $ebook)
                        <tr class="border-t">
                             <td class="p-3">{{ $loop->iteration }}</td>
                            <td class="p-3">
                                <img src="{{ asset('storage/' . $ebook->cover) }}" class="w-16 h-20 object-cover rounded" />
                            </td>
                            <td class="p-3">{{ $ebook->title }}</td>
                            <td class="p-3">{{ $ebook->author }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($ebook->published_at)->translatedFormat('d F Y') }}</td>
                            <td class="p-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('cms.ebooks.edit', $ebook->id) }}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 text-sm rounded">Edit</a>
                                    <form action="{{ route('cms.ebooks.destroy', $ebook->id) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus eBook ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const toggleBtn = document.getElementById('toggleLayoutBtn');
    const icon = document.getElementById('layoutIcon');

    let isGrid = true;

    toggleBtn.addEventListener('click', () => {
        isGrid = !isGrid;

        gridView.classList.toggle('hidden', !isGrid);
        tableView.classList.toggle('hidden', isGrid);

        // Ganti ikon
        if (isGrid) {
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />`; // list icon
        } else {
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 4H5a2 2 0 00-2 2v12a2 2 0 002 2h4M15 4h4a2 2 0 012 2v12a2 2 0 01-2 2h-4" />`; // grid icon
        }
    });
</script>
@endpush
