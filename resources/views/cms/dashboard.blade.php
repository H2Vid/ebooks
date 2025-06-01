@extends('layouts.cms') {{-- sesuaikan dengan layout yang kamu pakai --}}

@section('content')
<div class="relative  mx-auto px-6 py-10 min-h-screen bg-gray-50">

    {{-- Background perpustakaan tipis --}}
    <div class="absolute inset-0 pointer-events-none opacity-20 bg-cover bg-center"
         style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1350&q=80');">
    </div>

    {{-- Konten utama, beri relative agar di atas bg --}}
    <div class="relative z-10">

        {{-- Greeting --}}
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">
            Halo, <span class="text-green-600">{{ Auth::user()->name }}</span>! 👋<br>
            Selamat datang kembali di dashboard eBook.
        </h1>

        {{-- Statistik dan waktu --}}
        <div class="flex justify-between mb-12 w-full">
            <div class="w-auto bg-white rounded-lg shadow p-5 flex flex-col items-center justify-center">
                <div class="text-5xl font-bold text-green-600">{{ $totalEbooks }}</div>
                <div class="mt-2 text-gray-600 uppercase tracking-wide font-semibold text-sm">Total eBook</div>
            </div>
            <div class=" w-auto bg-white rounded-lg shadow p-5 flex flex-col justify-center">
                <div>
                    <div id="digitalClock" class="font-mono text-4xl text-green-700 tracking-widest"></div>
                    <div id="dateDisplay" class="text-green-600 mt-1"></div>
                </div>
                <div>
                    <label for="timezone" class="block text-green-700 font-semibold mb-1">Pilih Zona Waktu:</label>
                    <select id="timezone" class="border border-green-400 rounded px-3 py-1 text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="Asia/Jakarta">WIB </option>
                    <option value="Asia/Makassar">WITA</option>
                    <option value="Asia/Jayapura">WIT</option>
                    </select>
                </div>
                </div>
        </div>

        {{-- Judul ebook terbaru --}}
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">eBook Terbaru</h2>

   {{-- Tombol toggle layout --}}
            <div class="mb-4">
                <button id="toggleLayoutBtn"
                        class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                    <svg id="layoutIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        @if($ebooks->isEmpty())
            <p class="text-gray-500">Belum ada eBook yang diupload.</p>
        @else


            {{-- Grid View --}}
            <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ebooks as $ebook)
                    <div class="bg-white p-4 rounded shadow border relative">
                        <img src="{{ asset('storage/' . $ebook->cover_path) }}" class="w-full h-40 object-cover mb-3 rounded" alt="Cover eBook" />
                        <h4 class="text-lg font-semibold">{{ $ebook->title }}</h4>
                        <p class="text-sm text-gray-600">Penulis: {{ $ebook->author }}</p>
                        <p class="text-sm text-gray-500 mb-3">
                            Terbit: {{ \Carbon\Carbon::parse($ebook->published_at)->translatedFormat('d F Y') }}
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route('cms.ebooks.edit', $ebook->id) }}"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded transition">Edit</a>
                            <form action="{{ route('cms.ebooks.destroy', $ebook->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-sm rounded transition btn btn-danger btn-sm btn-confirm-delete">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

          {{-- Table View --}}
<div id="tableView" class="hidden overflow-x-auto mt-6 rounded border border-green-300 bg-white shadow-sm">
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
                    <td class="p-3 align-middle">#{{ $loop->iteration }}</td>
                    <td class="p-3">
                        <img src="{{ asset('storage/' . $ebook->cover_path) }}" alt="Cover eBook" class="w-16 h-20 object-cover rounded" />
                    </td>
                    <td class="p-3 align-middle font-medium text-gray-950">{{ $ebook->title }}</td>
                    <td class="p-3 align-middle text-gray-950">{{ $ebook->author }}</td>
                    <td class="p-3 align-middle text-gray-950">{{ \Carbon\Carbon::parse($ebook->published_at)->translatedFormat('d F Y') }}</td>
                    <td class="p-3 align-middle">
                        <div class="flex gap-2">
                            <a href="{{ route('cms.ebooks.edit', $ebook->id) }}"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded transition">
                                Edit
                            </a>
                             <form action="{{ route('cms.ebooks.destroy', $ebook->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-sm rounded transition btn btn-danger btn-sm btn-confirm-delete">Hapus</button>
                            </form>


                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        @endif

    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Toggle Layout
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const toggleBtn = document.getElementById('toggleLayoutBtn');
    const icon = document.getElementById('layoutIcon');

    let isGrid = true;

    toggleBtn.addEventListener('click', () => {
        isGrid = !isGrid;
        gridView.classList.toggle('hidden', !isGrid);
        tableView.classList.toggle('hidden', isGrid);

        if (isGrid) {
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />`; // list icon
        } else {
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 4H5a2 2 0 00-2 2v12a2 2 0 002 2h4M15 4h4a2 2 0 012 2v12a2 2 0 01-2 2h-4" />`; // grid icon
        }
    });

    // Update jam realtime WITA (GMT+8)
    const clockEl = document.getElementById('digitalClock');
  const dateEl = document.getElementById('dateDisplay');
  const timezoneSelect = document.getElementById('timezone');

  function updateClock(timezone) {
    const now = new Date();

    // Format waktu dengan Intl.DateTimeFormat supaya support timezone dan lokal Indonesia
    const optionsTime = {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: false,
      timeZone: timezone
    };

    const optionsDate = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      timeZone: timezone
    };

    const timeString = new Intl.DateTimeFormat('id-ID', optionsTime).format(now);
    const dateString = new Intl.DateTimeFormat('id-ID', optionsDate).format(now);

    clockEl.textContent = timeString;
    dateEl.textContent = dateString;
  }

  // Inisialisasi timezone default
  let currentTimezone = timezoneSelect.value;
  updateClock(currentTimezone);

  // Update tiap detik
  setInterval(() => {
    updateClock(currentTimezone);
  }, 1000);

  // Ganti timezone saat dropdown berubah
  timezoneSelect.addEventListener('change', (e) => {
    currentTimezone = e.target.value;
    updateClock(currentTimezone);
  });

  //toast hapus
   document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-confirm-delete');

        deleteButtons.forEach(button => {
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
    });
</script>
@endpush
