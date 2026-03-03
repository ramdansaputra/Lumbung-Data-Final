{{-- ================================================================ --}}
{{-- PANEL INFORMASI (klik ikon tanda tanya di topbar)               --}}
{{-- Simpan sebagai: resources/views/admin/partials/panel-informasi.blade.php --}}
{{-- Lalu @include di layout utama sebelum </body>                   --}}
{{-- ================================================================ --}}

{{-- Tambahkan di x-data layout utama: panelInfo: false --}}
{{-- Ubah tombol tanda tanya di topbar menjadi: @click="panelInfo = !panelInfo" --}}

<div x-data="{ panelInfo: false, activeSection: null }" @keydown.escape.window="panelInfo = false">

    {{-- Overlay --}}
    <div x-show="panelInfo" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="panelInfo = false"
        class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[900]" style="display:none"></div>

    {{-- Panel --}}
    <div x-show="panelInfo" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl z-[901] flex flex-col overflow-hidden"
        style="display:none">

        {{-- Header --}}
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 px-5 py-5 flex-shrink-0">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-base">Informasi & Bantuan</h3>
                </div>
                <button @click="panelInfo = false"
                    class="w-7 h-7 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-white/70 text-xs leading-relaxed">
                Lumbung Data adalah sistem informasi desa berbasis web yang dikembangkan menggunakan Laravel.
            </p>
        </div>

        {{-- Scrollable Content --}}
        <div class="flex-1 overflow-y-auto">

            {{-- Versi Aplikasi --}}
            <div class="px-5 py-4 bg-emerald-50 border-b border-emerald-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Versi Aplikasi</p>
                        <p class="text-sm font-bold text-emerald-700">Lumbung Data v1.0.0</p>
                    </div>
                    <span
                        class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">Aktif</span>
                </div>
            </div>

            {{-- Accordion Sections --}}
            <div class="divide-y divide-gray-100">

                {{-- Tentang Lumbung Data --}}
                <div x-data="{ open: true }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">Tentang Lumbung Data</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-4">
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Lumbung Data adalah aplikasi Sistem Informasi Desa (SID) yang dikembangkan menggunakan
                            framework Laravel. Sistem ini dirancang untuk membantu pengelolaan data desa secara
                            digital, transparan, dan efisien.
                        </p>
                        <div class="mt-3 space-y-2">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Manajemen data kependudukan
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Layanan surat digital
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Statistik & laporan otomatis
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Monitoring kesehatan warga
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan Rilis --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">Catatan Rilis</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-4">
                        <div class="space-y-3">
                            <div class="relative pl-4 border-l-2 border-emerald-300">
                                <div class="absolute -left-[5px] top-1 w-2 h-2 rounded-full bg-emerald-500"></div>
                                <p class="text-xs font-bold text-gray-700">v1.0.0 <span
                                        class="font-normal text-gray-400 ml-1">— 2025</span></p>
                                <ul class="mt-1 space-y-0.5 text-xs text-gray-500">
                                    <li>• Rilis perdana Lumbung Data</li>
                                    <li>• Modul kependudukan & keluarga</li>
                                    <li>• Layanan surat & arsip digital</li>
                                    <li>• Dashboard statistik interaktif</li>
                                    <li>• Sistem notifikasi real-time</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panduan Penggunaan --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">Panduan Penggunaan</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-4">
                        <div class="space-y-2">
                            <a href="/admin/bantuan"
                                class="flex items-center gap-2 p-2.5 rounded-lg bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 transition-colors group">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-xs font-medium">Dokumentasi Lengkap</span>
                                <svg class="w-3 h-3 ml-auto text-gray-300 group-hover:text-emerald-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <a href="/admin/bantuan#video"
                                class="flex items-center gap-2 p-2.5 rounded-lg bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 transition-colors group">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-medium">Tutorial Video</span>
                                <svg class="w-3 h-3 ml-auto text-gray-300 group-hover:text-emerald-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Hak Cipta & Ketentuan --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">Hak Cipta & Ketentuan</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-4">
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Lumbung Data dikembangkan sebagai proyek PKL. Seluruh fitur dan data yang tersimpan
                            merupakan milik desa yang menggunakan sistem ini. Penggunaan sistem wajib mematuhi
                            peraturan perundang-undangan yang berlaku di Indonesia.
                        </p>
                    </div>
                </div>

                {{-- Kontak & Informasi --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">Kontak & Informasi</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-5 pb-4">
                        <div class="space-y-2 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $desa->nama_desa ?? 'Nama Desa' }},
                                    {{ $desa->kecamatan ?? 'Kecamatan' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>admin@lumbungdata.desa.id</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div class="flex-shrink-0 px-5 py-3 bg-gray-50 border-t border-gray-100 text-center">
            <p class="text-[10px] text-gray-400">
                © {{ date('Y') }} Lumbung Data · Dikembangkan dengan ❤️ untuk desa
            </p>
        </div>
    </div>
</div>
