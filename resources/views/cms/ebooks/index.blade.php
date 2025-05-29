@extends('layouts.cms')

@section('content')
<div class="relative mx-auto px-6 py-10 min-h-screen bg-gray-50">

    <div class="relative z-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Daftar Semua eBook</h1>

            <button id="toggleLayoutBtn"
                class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                <svg id="layoutIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 4H5a2 2 0 00-2 2v12a2 2 0 002 2h4M15 4h4a2 2 0 012 2v12a2 2 0 01-2 2h-4" /> {{-- Grid icon by default --}}
                </svg>
            </button>
        </div>

        @if($ebooks->isEmpty())
            <p class="text-gray-500">Belum ada eBook yang tersedia.</p>
        @else

            {{-- TAMPILAN TABLE DEFAULT --}}
            <div id="tableView" class="overflow-x-auto rounded border border-green-300 bg-white shadow-sm">
                <table class="w-full text-left text-sm text-gray-700">
                    <thead class="bg-green-100 font-semibold text-black">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Cover</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">Tanggal Terbit</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ebooks as $ebook)
                            <tr class="border-t border-green-200 hover:bg-green-50 transition-colors">
                                <td class="p-3">#{{ $loop->iteration }}</td>
                                <td class="p-3">
                                    <img src="{{ asset('storage/' . $ebook->cover) }}" class="w-16 h-20 object-cover rounded" />
                                </td>
                                <td class="p-3 font-medium">{{ $ebook->title }}</td>
                                <td class="p-3">{{ $ebook->author }}</td>
                                <td class="p-3">{{ \Carbon\Carbon::parse($ebook->published_at)->translatedFormat('d F Y') }}</td>
                                <td class="p-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('cms.ebooks.edit', $ebook->id) }}"
                                           class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded">Edit</a>
                                        <form action="{{ route('cms.ebooks.destroy', $ebook->id) }}" method="POST" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-sm rounded btn-confirm-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TAMPILAN GRID --}}
            <div id="gridView" class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                @foreach($ebooks as $ebook)
                    <div class="bg-white p-4 rounded shadow border">
                        <img src="{{ asset('storage/' . $ebook->cover) }}" class="w-full h-40 object-cover mb-3 rounded" />
                        <h4 class="text-lg font-semibold">{{ $ebook->title }}</h4>
                        <p class="text-sm text-gray-600">Penulis: {{ $ebook->author }}</p>
                        <p class="text-sm text-gray-500 mb-3">
                            Terbit: {{ \Carbon\Carbon::parse($ebook->published_at)->translatedFormat('d F Y') }}
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route('cms.ebooks.edit', $ebook->id) }}"
                               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded">Edit</a>
                            <form action="{{ route('cms.ebooks.destroy', $ebook->id) }}" method="POST" class="delete-form">
                                @csrf @method('DELETE')
                                <button type="button"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-sm rounded btn-confirm-delete">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const toggleBtn = document.getElementById('toggleLayoutBtn');
    const icon = document.getElementById('layoutIcon');

    let isGrid = false; // Mulai dengan table view
    gridView.classList.add('hidden');
    tableView.classList.remove('hidden');

    toggleBtn.addEventListener('click', () => {
        isGrid = !isGrid;
        gridView.classList.toggle('hidden', !isGrid);
        tableView.classList.toggle('hidden', isGrid);

        icon.innerHTML = isGrid
            ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M4 6h16M4 12h16M4 18h16" />` // List icon
            : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M9 4H5a2 2 0 00-2 2v12a2 2 0 002 2h4M15 4h4a2 2 0 012 2v12a2 2 0 01-2 2h-4" />`; // Grid icon
    });

    // SweetAlert Hapus
    document.querySelectorAll('.btn-confirm-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data tidak bisa dikembalikan setelah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
