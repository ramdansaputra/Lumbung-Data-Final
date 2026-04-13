@extends('superadmin.layout.superadmin')

@section('title', 'Edit User')
@section('header', 'Edit User')
@section('subheader', 'Perbarui data pengguna yang sudah terdaftar.')

@section('content')

<div style="max-width: 720px;">

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex gap-3">
        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
            <svg width="16" height="16" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-red-600 mb-1">Terdapat kesalahan input:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">

        {{-- Header Card --}}
        <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            <div>
                <p class="text-sm font-extrabold text-slate-800">Edit User: {{ $user->name }}</p>
                <p class="text-xs text-slate-400 font-medium">Kosongkan password jika tidak ingin mengubahnya</p>
            </div>
        </div>

        <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST" class="p-8 space-y-5">
            @csrf
            @method('PUT')

            {{-- Grid 2 kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Nama lengkap..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all placeholder:font-normal placeholder:text-slate-400" required>
                </div>

                {{-- Username --}}
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Username <span class="text-red-400">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" placeholder="Username unik..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all placeholder:font-normal placeholder:text-slate-400" required>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Alamat Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@email.com"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all placeholder:font-normal placeholder:text-slate-400" required>
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Role <span class="text-red-400">*</span></label>
                    <select name="role" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <option value="" disabled>-- Pilih Role --</option>
                        <option value="admin"      {{ old('role', $user->role) == 'admin'      ? 'selected' : '' }}>Admin</option>
                        <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    </select>
                </div>
            </div>

            {{-- Password (full width) --}}
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">
                    Password
                    <span class="normal-case font-medium text-slate-400 ml-1">(kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input type="password" name="password" placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all placeholder:font-normal placeholder:text-slate-400">
            </div>

            {{-- Divider --}}
            <div class="border-t border-slate-100 pt-5 flex items-center gap-3">
                <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-100 flex items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('superadmin.users.index') }}"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-all">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection