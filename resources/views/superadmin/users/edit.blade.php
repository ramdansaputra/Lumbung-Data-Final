@extends('superadmin.layout.superadmin')

@section('title', 'Edit User')
@section('header', 'Edit User')
@section('subheader', 'Perbarui data pengguna yang sudah terdaftar.')

@section('content')

<div style="max-width: 680px;">

    {{-- Error Validation --}}
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
        <p class="text-sm font-bold text-red-600 mb-2">Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-red-500">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-8 space-y-6">

        <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    placeholder="Masukkan nama lengkap..."
                    required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                >
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Alamat Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    placeholder="contoh@email.com"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                >
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">
                    Password
                    <span class="normal-case font-medium text-slate-400 ml-1">(kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input
                    type="password"
                    name="password"
                    placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                >
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-2">Role</label>
                <select
                    name="role"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                >
                    <option value="" disabled>-- Pilih Role --</option>
                    <option value="admin"      {{ old('role', $user->role) == 'admin'      ? 'selected' : '' }}>Admin</option>
                    <option value="operator"   {{ old('role', $user->role) == 'operator'   ? 'selected' : '' }}>Operator</option>
                    <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-100"
                >
                    Simpan Perubahan
                </button>
                <a
                    href="{{ route('superadmin.users.index') }}"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-all"
                >
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection