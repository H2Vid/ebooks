<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-image: linear-gradient(to left, rgba(0,0,0,0.6), rgba(0,0,0,0.2)), url('https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="flex flex-col md:flex-row w-full max-w-5xl rounded-xl overflow-hidden">

        <!-- Form Login -->
        <div class="w-full md:w-1/2 p-8 md:p-12 bg-white/40 backdrop-blur-md rounded-l-xl">
            <h2 class="text-3xl font-bold text-green-800 mb-6">Login Admin</h2>

               @if (session('success') || session('error'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-show="show"
                    x-transition
                    class="px-6 py-4 rounded-lg shadow-lg
                        text-white font-semibold
                        {{ session('success') ? 'bg-green-600' : 'bg-red-600' }}">
                    {{ session('success') ?? session('error') }}
                </div>
                @endif

            <form method="POST" action="{{ route('cms.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-green-900 font-semibold mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-4 py-2.5 bg-white/80 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" />
                </div>

                <div>
                    <label for="password" class="block text-green-900 font-semibold mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2.5 bg-white/80 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" />
                </div>

                <div class="pt-2 space-y-4">
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition duration-200">
                        Login
                    </button>

                    <div class="text-center">
                        <a href="{{ route('cms.register') }}" class="text-sm text-green-800 hover:underline font-medium">
                            Belum punya akun? Daftar di sini
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Teks Promo (tanpa background khusus) -->
       <!-- Teks Promo ala Travel Explore -->
<div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center text-white space-y-6">
    <div class="text-sm tracking-widest uppercase text-white/90 font-semibold">
        CMS EBOOK
    </div>
    <h2 class="text-5xl md:text-6xl font-black uppercase leading-tight drop-shadow-lg">
        Kelola <br /> Koleksi Digital
    </h2>
    <p class="text-lg text-white/80 font-medium">
        Tempat terbaik untuk mengatur, menyimpan, dan membagikan eBook profesional Anda.
    </p>
    <p class="text-sm text-white/70">
        Bangun perpustakaan digital sendiri dan distribusikan ke seluruh penjuru negeri dengan efisien dan cepat.
    </p>
</div>

    </div>

</body>
</html>
