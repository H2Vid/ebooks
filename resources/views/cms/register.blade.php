<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <form method="POST" action="{{ route('cms.register') }}" class="bg-white p-6 rounded shadow w-full max-w-sm">
        @csrf

        <h2 class="text-xl font-semibold mb-4">Register Admin</h2>

        @if($errors->any())
            <div class="text-red-600 mb-3">{{ $errors->first() }}</div>
        @endif

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" required class="w-full border rounded p-2">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" required class="w-full border rounded p-2">
        </div>

        <button class="w-full bg-green-500 text-white py-2 rounded">Register</button>
    </form>
</body>
</html>
