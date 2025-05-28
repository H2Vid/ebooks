<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <form method="POST" action="{{ route('cms.login') }}" class="bg-white p-6 rounded shadow w-full max-w-sm">
        @csrf

        <h2 class="text-xl font-semibold mb-4">Login Admin</h2>

        @if(session('error'))
            <div class="text-red-600 mb-3">{{ session('error') }}</div>
        @endif

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" required class="w-full border rounded p-2">
        </div>

        <button class="w-full bg-blue-500 text-white py-2 rounded">Login</button>
    </form>
</body>
</html>
