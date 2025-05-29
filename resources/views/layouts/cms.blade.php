<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard') – CMS eBook</title>

    {{-- Tailwind + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="w-64 bg-white border-r shadow-lg flex flex-col">
        {{-- Header --}}
        <div class="p-6 bg-green-600 text-white border-b border-green-700">
            <h1 class="text-2xl font-bold">CMS eBook</h1>
            <p class="text-sm mt-1">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span></p>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 px-4 py-6 space-y-2 bg-white">
            <a href="{{ route('cms.dashboard') }}"
               class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition font-medium {{ request()->routeIs('cms.dashboard') ? 'bg-green-100 text-green-700' : '' }}">
                <span class="text-xl mr-3">🏠</span> Dashboard
            </a>

            <a href="{{ route('cms.ebooks.create') }}"
               class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition font-medium {{ request()->routeIs('cms.ebooks.create') ? 'bg-green-100 text-green-700' : '' }}">
                <span class="text-xl mr-3">➕</span> Upload eBook
            </a>

            <a href=""
               class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-700 transition font-medium {{ request()->routeIs('cms.ebooks.index') ? 'bg-green-100 text-green-700' : '' }}">
                <span class="text-xl mr-3">📚</span> Daftar eBook
            </a>
        </nav>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('cms.logout') }}" class="p-6 border-t border-gray-200">
            @csrf
            <button
                class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 transition text-white py-2 rounded-lg text-sm font-semibold">
                🔒 Logout
            </button>
        </form>
    </aside>

    {{-- ================= KONTEN UTAMA ================= --}}
    <main class="flex-1 p-10 bg-gray-50">
        @yield('content')
    </main>

    {{-- Extra scripts --}}
    @stack('scripts')
</body>
</html>
