<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Baru</title>
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

        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Buat Password Baru</h2>

        @error('email')
            <div class="mb-5 text-sm font-medium text-red-800 bg-red-100 border border-red-200 p-3 rounded-md text-center">
                {{ $message }}
            </div>
        @enderror

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                    class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed outline-none">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" id="password" name="password" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors @error('password') border-red-500 @enderror">
                @error('password')
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-colors">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 px-4 rounded-md transition-colors shadow-sm">
                Simpan Password Baru
            </button>
        </form>
    </div>

</body>
</html>