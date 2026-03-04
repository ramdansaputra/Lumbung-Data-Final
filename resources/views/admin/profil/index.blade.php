@extends('layouts.admin')

@section('title', 'Profil Pengguna')

@section('content')

    @php
        $user = Auth::user();
        $initials = strtoupper(substr($user->name ?? 'Ad', 0, 2));
        $roleName = 'Administrator';
        if (method_exists($user, 'getRoleNames')) {
            $roleName = $user->getRoleNames()->first() ?? 'Administrator';
        } elseif (!empty($user->role)) {
            $roleName = $user->role;
        }
    @endphp

    <div class="flex flex-col md:flex-row gap-6" x-data="{ activeTab: 'profil' }">

        {{-- Sidebar Foto --}}
        <div class="md:w-60 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Avatar --}}
                <div
                    class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-700 px-5 py-8 flex flex-col items-center text-center">
                    @if (!empty($user->foto))
                        <img src="{{ asset('storage/' . $user->foto) }}"
                            class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white/30 shadow-xl"
                            alt="{{ $user->name }}">
                    @else
                        <div
                            class="w-20 h-20 rounded-2xl bg-white/20 flex items-center justify-center text-white font-bold text-2xl ring-4 ring-white/30 shadow-xl">
                            {{ $initials }}
                        </div>
                    @endif
                    <p class="mt-3 font-bold text-white text-sm">{{ $user->name ?? 'Pengguna' }}</p>
                    <p class="text-white/60 text-xs mt-0.5">{{ $user->email ?? '-' }}</p>
                    <span class="mt-2 px-2.5 py-0.5 bg-white/20 text-white text-xs font-medium rounded-full">
                        {{ $roleName }}
                    </span>
                </div>

                {{-- Upload Foto --}}
                <div class="p-4">
                    <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="username" value="{{ $user->username ?? '' }}">

                        <label class="block text-xs font-semibold text-gray-600 mb-2">Ganti Foto</label>
                        <input type="file" name="foto" id="inputFoto" accept="image/jpeg,image/png,image/gif"
                            class="w-full text-xs text-gray-500 border border-gray-200 rounded-xl p-2
                               file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                               file:text-xs file:font-medium file:bg-emerald-50 file:text-emerald-700
                               hover:file:bg-emerald-100 cursor-pointer"
                            onchange="document.getElementById('btnSimpanFoto').style.display='block'">
                        <p class="text-xs text-gray-400 mt-1.5">JPG, PNG, GIF · Maks. 2MB</p>
                        <button type="submit" id="btnSimpanFoto" style="display:none"
                            class="mt-2 w-full py-2 bg-gradient-to-br from-emerald-500 to-teal-600
                               text-white text-xs font-semibold rounded-xl hover:shadow-lg
                               hover:shadow-emerald-500/25 transition-all">
                            Simpan Foto
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="flex-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Tabs --}}
                <div class="flex gap-1 p-1 bg-gray-50 border-b border-gray-100">
                    <button @click="activeTab = 'profil'"
                        :class="activeTab === 'profil'
                            ?
                            'bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-sm' :
                            'text-gray-500 hover:text-gray-700 hover:bg-white'"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil
                    </button>
                    <button @click="activeTab = 'sandi'"
                        :class="activeTab === 'sandi'
                            ?
                            'bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-sm' :
                            'text-gray-500 hover:text-gray-700 hover:bg-white'"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ubah Password
                    </button>
                </div>

                {{-- Tab: Profil --}}
                <div x-show="activeTab === 'profil'">
                    <div class="px-6 py-4 border-b border-gray-50">
                        <h3 class="font-semibold text-gray-700">Informasi Akun</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Perbarui data profil Anda</p>
                    </div>
                    <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data"
                        class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                   focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                   focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all"
                                required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                   focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <a href="/admin/dashboard"
                                class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium
                                   text-gray-600 hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-5 py-2.5 bg-gradient-to-br from-emerald-500 to-teal-600 text-white
                                   rounded-xl text-sm font-medium hover:shadow-lg hover:shadow-emerald-500/25 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tab: Ubah Password --}}
                <div x-show="activeTab === 'sandi'" style="display:none" x-data="{ showOld: false, showNew: false, showConfirm: false }">
                    <div class="px-6 py-4 border-b border-gray-50">
                        <h3 class="font-semibold text-gray-700">Ubah Password</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Perbarui password Anda secara berkala</p>
                    </div>
                    <form action="{{ route('admin.profil.password') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Password Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showOld ? 'text' : 'password'" name="current_password"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm
                                       focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none"
                                    placeholder="Password sekarang" required>
                                <button type="button" @click="showOld = !showOld"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showOld" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showOld" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="display:none">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showNew ? 'text' : 'password'" name="new_password"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm
                                       focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none"
                                    placeholder="Minimal 8 karakter" required>
                                <button type="button" @click="showNew = !showNew"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showNew" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="display:none">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'" name="new_password_confirmation"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm
                                       focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none"
                                    placeholder="Ulangi password baru" required>
                                <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showConfirm" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" style="display:none">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('new_password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <button type="reset"
                                class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-medium
                                   text-gray-600 hover:bg-gray-50 transition-colors">
                                Reset
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-gradient-to-br from-emerald-500 to-teal-600 text-white
                                   rounded-xl text-sm font-medium hover:shadow-lg hover:shadow-emerald-500/25 transition-all">
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
