<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard') – CMS eBook</title>

    {{-- Tailwind + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="w-64 bg-white shadow-lg flex flex-col">
        <div class="p-6 border-b">
            <h1 class="text-xl font-semibold">CMS eBook</h1>
            <p class="text-sm text-gray-500 mt-1">
                Halo, <span class="font-medium">{{ Auth::user()->name }}</span>
            </p>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 py-4 space-y-1">
            <x-cms-link route="cms.dashboard"   icon="🏠">Dashboard</x-cms-link>
            <x-cms-link route="cms.ebooks.create" icon="➕">Upload eBook</x-cms-link>
            <x-cms-link route="cms.ebooks.index"  icon="📚">Daftar eBook</x-cms-link>
        </nav>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('cms.logout') }}" class="p-6 border-t">
            @csrf
            <button
                class="w-full bg-red-500 hover:bg-red-600 transition text-white py-2 rounded text-sm font-medium">
                Logout
            </button>
        </form>
    </aside>

    {{-- ================= KONTEN UTAMA ================= --}}
    <main class="flex-1 p-10">
        @yield('content')
    </main>

    {{-- Extra scripts --}}
    @stack('scripts')
</body>
</html>
