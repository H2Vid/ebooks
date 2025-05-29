<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    @vite('resources/css/app.css')

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

        <!-- Form Register -->
        <div class="w-full md:w-1/2 p-8 md:p-12 bg-white/40 backdrop-blur-md rounded-l-xl">
            <h2 class="text-3xl font-bold text-green-800 mb-6">Register Admin</h2>

           @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


            <form method="POST" action="{{ route('cms.register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-green-900 font-semibold mb-1">Nama</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2.5 bg-white/80 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" />
                </div>

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
                        Register
                    </button>

                    <div class="text-center">
                        <a href="{{ route('cms.login') }}" class="text-sm text-green-800 hover:underline font-medium">
                            Sudah punya akun? Login di sini
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Teks Promo sama seperti halaman login -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center text-white space-y-6">
            <div class="text-sm tracking-widest uppercase text-white/90 font-semibold">
                CMS EBOOK
            </div>
            <h2 class="text-5xl md:text-6xl font-black uppercase leading-tight drop-shadow-lg">
                Daftarkan <br /> Akun Admin
            </h2>
            <p class="text-lg text-white/80 font-medium">
                Bergabunglah dan mulai kelola koleksi eBook profesional Anda secara efisien.
            </p>
            <p class="text-sm text-white/70">
                Bangun perpustakaan digital modern hanya dengan beberapa klik.
            </p>
        </div>

    </div>

</body>
</html>
