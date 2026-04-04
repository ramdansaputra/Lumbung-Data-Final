<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    @vite('resources/css/app.css') 
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        
        @if(isset($desa))
            <div class="flex justify-center mb-4">
                <h1 class="text-xl font-bold text-emerald-600 uppercase tracking-wider">
                    {{ $desa->nama_desa ?? 'SISTEM DESA' }}
                </h1>
            </div>
        @endif

        <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">Lupa Password?</h2>
        <p class="text-sm text-gray-600 text-center mb-6">
            Masukkan alamat email yang terdaftar, dan kami akan mengirimkan instruksi untuk mereset password Anda.
        </p>

        @if (session('status'))
            <div class="mb-5 text-sm font-medium text-emerald-800 bg-emerald-100 border border-emerald-200 p-3 rounded-md text-center">
                {{ session('status') }}
            </div>
        @endif

        @error('email')
            <div class="mb-5 text-sm font-medium text-red-800 bg-red-100 border border-red-200 p-3 rounded-md text-center">
                {{ $message }}
            </div>
        @enderror

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors @error('email') border-red-500 @enderror"
                    placeholder="nama@email.com">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 px-4 rounded-md transition-colors shadow-sm">
                Kirim Link Reset
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium hover:underline transition-colors">
                &larr; Kembali ke halaman Login
            </a>
        </div>
    </div>

</body>
</html>