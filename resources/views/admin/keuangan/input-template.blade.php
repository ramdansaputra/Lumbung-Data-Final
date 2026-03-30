@extends('layouts.admin')

@section('content')

<div>

    {{-- Page Top Header --}}
    <div style="margin-bottom: 20px;">
        <div>
            <h1>Template Anggaran Keuangan</h1>
            <p>Kelola data anggaran keuangan desa</p>
        </div>
        <nav style="margin-top: 10px; color: #666;">
            <a href="#" style="text-decoration: none; color: #0056b3;">Beranda</a>
            <span style="margin: 0 5px;">›</span>
            <span>Template Anggaran</span>
        </nav>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Card --}}
    <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">

        {{-- Action Row --}}
        <div style="margin-bottom: 20px; display: flex; gap: 10px;">
            <button type="button" onclick="openModal('modalTambahTemplate')" style="padding: 8px 15px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                + Tambah Template
            </button>
            <button type="button" style="padding: 8px 15px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                Impor / Ekspor
            </button>
        </div>

        {{-- Filter + Controls --}}
        <form action="{{ route('admin.keuangan.input.index') }}" method="GET" id="filterForm" style="margin-bottom: 30px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 15px;">

            {{-- Filter Row --}}
            <div style="display: flex; gap: 10px;">
                <select name="tahun" onchange="document.getElementById('filterForm').submit()" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $tahunDipilih == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endforeach
                </select>

                <select name="status_rekening" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="">Pilih Status Rekening</option>
                    <option value="induk">Induk</option>
                    <option value="detail">Detail</option>
                </select>

                <select name="jenis_akun" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="">Pilih Jenis Akun</option>
                    <option value="pendapatan">Pendapatan</option>
                    <option value="belanja">Belanja</option>
                    <option value="pembiayaan">Pembiayaan</option>
                </select>
            </div>

            {{-- Controls Row --}}
            <div style="display: flex; gap: 15px; align-items: center;">
                <div>
                    Tampilkan
                    <select name="per_page" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    entri
                </div>
                <div>
                    <label>Cari:</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="kata kunci pencarian" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">
                    <button type="submit" style="padding: 6px 12px; background: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Cari</button>
                </div>
            </div>

        </form>

        {{-- ========================================================= --}}
        {{-- DATA ANGGARAN DENGAN PEMISAHAN KELOMPOK (LEVEL 1 & LEVEL 2) --}}
        {{-- ========================================================= --}}
        
        @if(isset($groupedData) && count($groupedData) > 0)
            @foreach($groupedData as $lvl1Kode => $dataL1)
                
                {{-- BLOK UTAMA LEVEL 1 (Contoh: 4, 5, 6) --}}
                <div style="border: 1px solid #adb5bd; margin-bottom: 40px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    
                    {{-- Header Induk Level 1 --}}
                    @if($dataL1['induk'])
                        @php $itemInduk = $dataL1['induk']; @endphp
                        <div style="background: #343a40; color: #fff; padding: 15px 20px;">
                            <h2 style="margin: 0; font-size: 18px; display: flex; justify-content: space-between;">
                                <span>{{ $itemInduk->akunRekening->kode_rekening }} - {{ strtoupper($itemInduk->akunRekening->uraian) }}</span>
                            </h2>
                        </div>
                        
                        {{-- Row Master Induk (Disembunyikan format tabelnya agar tampil seperti header summary) --}}
                        <table class="tabel-anggaran" width="100%" cellpadding="12" cellspacing="0" style="background: #e9ecef; border-bottom: 3px solid #ced4da;">
                            <tbody>
                                <tr data-id="{{ $itemInduk->id }}" data-kode="{{ $itemInduk->akunRekening->kode_rekening }}" 
                                    data-anggaran="{{ $itemInduk->anggaran }}" data-realisasi="{{ $itemInduk->realisasi }}" data-editable="0" style="font-weight: bold; font-size: 16px;">
                                    <td width="60%" style="text-align: right; text-transform: uppercase;">TOTAL KESELURUHAN {{ $itemInduk->akunRekening->uraian }} :</td>
                                    <td width="20%" style="color: #0056b3;">Rp <span class="anggaran-display">{{ number_format($itemInduk->anggaran, 0, ',', '.') }}</span></td>
                                    <td width="20%" style="color: #28a745;">Rp <span class="realisasi-display">{{ number_format($itemInduk->realisasi, 0, ',', '.') }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif

                    {{-- LOOPING LEVEL 2 (Contoh: 4.1, 4.2, 4.3 dst) --}}
                    <div style="padding: 20px; background: #fdfdfd;">
                        @foreach($dataL1['kelompok'] as $lvl2Kode => $dataL2)
                            
                            <div style="margin-bottom: 30px; border: 1px solid #dee2e6; border-radius: 6px; overflow: hidden;">
                                {{-- Tabel Khusus per Kelompok --}}
                                <table class="tabel-anggaran" border="1" width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; border-color: #dee2e6;">
                                    <thead style="background: #f8f9fa;">
                                        <tr>
                                            <th width="4%" style="text-align: center;"><input type="checkbox" class="checkAllGroup" title="Pilih Semua di kelompok ini"></th>
                                            <th width="5%" style="text-align: center;">NO</th>
                                            <th width="10%" style="text-align: center;">AKSI</th>
                                            <th width="12%">KODE</th>
                                            <th width="35%">URAIAN</th>
                                            <th width="17%">ANGGARAN</th>
                                            <th width="17%">REALISASI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Row Header Level 2 (Induk Kelompok) --}}
                                        @if($dataL2['header'])
                                            @php $itemHdr = $dataL2['header']; $kodeHdr = $itemHdr->akunRekening->kode_rekening; @endphp
                                            <tr data-id="{{ $itemHdr->id }}" data-kode="{{ $kodeHdr }}" 
                                                data-anggaran="{{ $itemHdr->anggaran }}" data-realisasi="{{ $itemHdr->realisasi }}" 
                                                data-editable="0" style="background: #e3f2fd; font-weight: bold;">
                                                <td colspan="3" style="text-align: center; color: #666;"><span title="Total Kelompok Level 2">🔒 Auto</span></td>
                                                <td>{{ $kodeHdr }}</td>
                                                <td>{{ $itemHdr->akunRekening->uraian }}</td>
                                                <td>Rp <span class="anggaran-display">{{ number_format($itemHdr->anggaran, 0, ',', '.') }}</span></td>
                                                <td>Rp <span class="realisasi-display">{{ number_format($itemHdr->realisasi, 0, ',', '.') }}</span></td>
                                            </tr>
                                        @endif

                                        {{-- Row Items Level 3 ke atas (Detail) --}}
                                        @foreach($dataL2['items'] as $index => $item)
                                            @php
                                                // Spasi indentasi
                                                $level = substr_count($item->akunRekening->kode_rekening, '.') - 1; 
                                                $level = $level < 0 ? 0 : $level;
                                                $isInduk = !$item->akunRekening->is_editable;
                                                $kode = $item->akunRekening->kode_rekening;
                                            @endphp
                                            <tr data-id="{{ $item->id }}" data-kode="{{ $kode }}" 
                                                data-anggaran="{{ $item->anggaran }}" data-realisasi="{{ $item->realisasi }}" 
                                                data-editable="{{ $isInduk ? '0' : '1' }}">
                                                
                                                <td style="text-align: center;">
                                                    @if(!$isInduk) 
                                                        <input type="checkbox" class="cb-row" value="{{ $item->id }}"> 
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">{{ $index + 1 }}</td>
                                                <td style="text-align: center;">
                                                    @if(!$isInduk)
                                                        <div style="position:relative;display:inline-block;" id="group-{{ $item->id }}">
                                                            <button type="button" onclick="toggleDropdown('dd-{{ $item->id }}', event)" style="padding: 4px 8px; font-size: 12px; cursor: pointer;">
                                                                Aksi ▾
                                                            </button>
                                                            <div id="dd-{{ $item->id }}" style="display:none;position:absolute;background:#fff;border:1px solid #ccc;z-index:99;min-width:140px;text-align:left;box-shadow: 0 4px 8px rgba(0,0,0,0.1); top: 100%; left: 0;">
                                                                <button type="button" style="display:block;width:100%;padding:10px;background:none;border:none;border-bottom:1px solid #eee;text-align:left;cursor:pointer;" onclick="openEditModal(
                                                                    {{ $item->id }}, {{ $item->anggaran }}, {{ $item->realisasi }}, 
                                                                    '{{ addslashes($item->akunRekening->uraian) }}', '{{ $kode }}'
                                                                )">✏️ Edit Nominal</button>
                                                                <button type="button" style="display:block;width:100%;padding:10px;background:none;border:none;text-align:left;cursor:pointer;color:red;" onclick="openHapusModal(
                                                                    {{ $item->id }}, '{{ addslashes($item->akunRekening->uraian) }}'
                                                                )">🗑️ Hapus</button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span style="color: #888; font-size: 12px;" title="Akun Sub-Induk dihitung otomatis">🔒 Auto</span>
                                                    @endif
                                                </td>
                                                <td>{{ $kode }}</td>
                                                <td>
                                                    {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}{{ $item->akunRekening->uraian }}
                                                    @if($isInduk) <small style="color:#888;">(Σ)</small> @endif
                                                </td>
                                                <td>Rp <span class="anggaran-display">{{ number_format($item->anggaran, 0, ',', '.') }}</span></td>
                                                <td>Rp <span class="realisasi-display">{{ number_format($item->realisasi, 0, ',', '.') }}</span></td>
                                            </tr>
                                        @endforeach

                                        @if(count($dataL2['items']) == 0)
                                            <tr><td colspan="7" style="text-align: center; color: #888; padding: 15px;">Belum ada sub-rekening di kelompok ini.</td></tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>

                        @endforeach
                    </div>{{-- /.padding --}}
                </div>{{-- /.blok-utama --}}

            @endforeach
        @else
            {{-- Fallback jika tabel kosong sama sekali --}}
            <table id="tabelAnggaranKosong" border="1" width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse; border-color: #dee2e6;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th>NO</th><th>KODE REKENING</th><th>URAIAN</th><th>ANGGARAN</th><th>REALISASI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px; color: #666;">
                            Data kosong untuk tahun {{ $tahunDipilih }}. Klik <strong>Tambah Template</strong> untuk memulai.
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

    </div>{{-- /.main-card --}}
</div>


{{-- ========================================================= --}}
{{-- MODALS SECTION (Ditanam Langsung) --}}
{{-- ========================================================= --}}

{{-- ══ Modal: Tambah Template ══ --}}
<div id="modalTambahTemplate" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:200;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:25px;border-radius:8px;width:400px;max-width:95vw;box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        <h3 style="margin-top:0;">Tambah Template</h3>
        <p style="color:#666; margin-bottom: 20px;">Buat template anggaran untuk tahun baru</p>
        <form action="{{ route('admin.keuangan.input.tambah-template') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px; font-weight:bold;">Tahun Anggaran</label>
                <input type="number" name="tahun_baru" required value="{{ date('Y') + 1 }}" min="{{ date('Y') }}" max="{{ date('Y') + 10 }}" style="width:100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modalTambahTemplate')" style="padding: 8px 15px; background: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 15px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ Modal: Edit Nominal ══ --}}
<div id="modalEditNominal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:200;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:25px;border-radius:8px;width:400px;max-width:95vw;box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        <h3 style="margin-top:0;">Edit Nominal</h3>
        <p style="color:#666; margin-bottom: 20px;">Ubah nilai anggaran &amp; realisasi</p>
        <form id="formEditNominal" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="edit_kode_rekening" value="">
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:bold;">Uraian Rekening</label>
                <input type="text" id="edit_uraian" readonly style="width:100%; padding: 8px; background:#e9ecef; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; cursor: not-allowed;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight:bold;">Anggaran (Rp)</label>
                <input type="number" name="anggaran" id="edit_anggaran" required min="0" step="1" style="width:100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display:block; margin-bottom: 5px; font-weight:bold;">Realisasi (Rp)</label>
                <input type="number" name="realisasi" id="edit_realisasi" required min="0" step="1" style="width:100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modalEditNominal')" style="padding: 8px 15px; background: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 15px; background: #28a745; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ Modal: Konfirmasi Hapus ══ --}}
<div id="modalHapus" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:200;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:25px;border-radius:8px;width:400px;max-width:95vw;box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        <h3 style="margin-top:0; color: #dc3545;">Hapus Data</h3>
        <p>Tindakan ini tidak dapat dibatalkan.</p>
        <p style="margin-bottom: 25px;">Data anggaran untuk rekening <strong id="hapus_uraian">—</strong> akan dihapus secara permanen dari tahun ini.</p>
        <form id="formHapus" method="POST">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modalHapus')" style="padding: 8px 15px; background: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 15px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}
<script>
(function () {
    'use strict';

    /* ════ 1. MODAL & DROPDOWN HANDLER ════ */
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    window.openModal = openModal; window.closeModal = closeModal;

    // Tutup dropdown jika klik sembarang tempat
    function closeAllDropdowns() { 
        document.querySelectorAll('[id^="dd-"]').forEach(d => d.style.display = 'none'); 
    }
    
    function toggleDropdown(id, event) {
        event.stopPropagation();
        var dd = document.getElementById(id); 
        var wasOpen = dd.style.display === 'block';
        closeAllDropdowns(); 
        dd.style.display = wasOpen ? 'none' : 'block';
    }
    
    document.addEventListener('click', closeAllDropdowns);
    window.toggleDropdown = toggleDropdown; 
    window.closeAllDropdowns = closeAllDropdowns;

    // Klik overlay / Escape untuk tutup modal
    ['modalTambahTemplate', 'modalEditNominal', 'modalHapus'].forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('click', function (e) {
            if (e.target === el) closeModal(id);
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal('modalTambahTemplate');
            closeModal('modalEditNominal');
            closeModal('modalHapus');
            closeAllDropdowns();
        }
    });


    /* ════ 2. LOGIKA AUTO-SUM LINTAS TABEL ════ */
    function getRowMap() {
        var map = {};
        // Selector ini mendeteksi SELURUH tr yang punya data-kode di semua blok tabel
        document.querySelectorAll('.tabel-anggaran tbody tr[data-kode]').forEach(function (tr) {
            map[tr.dataset.kode] = {
                row       : tr,
                anggaran  : parseFloat(tr.dataset.anggaran)  || 0,
                realisasi : parseFloat(tr.dataset.realisasi) || 0,
                editable  : tr.dataset.editable === '1' // Menandai akun daun/detail
            };
        });
        return map;
    }

    function getParentKode(kode) {
        var lastDot = kode.lastIndexOf('.');
        return lastDot > -1 ? kode.substring(0, lastDot) : null;
    }

    function recalcParents(changedKode, newAnggaran, newRealisasi) {
        var map = getRowMap();
        
        // Update memori untuk baris yang diubah
        if (map[changedKode]) {
            map[changedKode].anggaran  = newAnggaran;
            map[changedKode].realisasi = newRealisasi;
            updateRowDisplay(map[changedKode].row, newAnggaran, newRealisasi);
        }

        // Cari silsilah parent (misal: 4.1.1 -> 4.1 -> 4)
        var ancestors = [];
        var cursor = getParentKode(changedKode);
        while (cursor) { ancestors.push(cursor); cursor = getParentKode(cursor); }

        // Hitung ulang setiap parent
        ancestors.forEach(function (parentKode) {
            if (!map[parentKode]) return;
            var sumA = 0, sumR = 0;
            
            Object.keys(map).forEach(function (k) {
                // Hitung jika akun detail (editable) dan merupakan turunan dari parent ini
                if (k.indexOf(parentKode + '.') === 0 && map[k].editable) {
                    sumA += map[k].anggaran;
                    sumR += map[k].realisasi;
                }
            });
            
            map[parentKode].anggaran  = sumA;
            map[parentKode].realisasi = sumR;
            updateRowDisplay(map[parentKode].row, sumA, sumR);
        });
    }

    // Eksekusi awal saat halaman diload untuk akurasi data
    function calculateAllParentsOnLoad() {
        var map = getRowMap();
        Object.keys(map).forEach(function(kode) {
            if (!map[kode].editable) {
                var sumA = 0, sumR = 0;
                Object.keys(map).forEach(function(childKode) {
                    if (childKode.indexOf(kode + '.') === 0 && map[childKode].editable) {
                        sumA += map[childKode].anggaran; 
                        sumR += map[childKode].realisasi;
                    }
                });
                updateRowDisplay(map[kode].row, sumA, sumR);
            }
        });
    }

    function updateRowDisplay(tr, anggaran, realisasi) {
        var a = tr.querySelector('.anggaran-display');
        var r = tr.querySelector('.realisasi-display');
        if (a) a.textContent = Math.round(anggaran).toLocaleString('id-ID');
        if (r) r.textContent = Math.round(realisasi).toLocaleString('id-ID');
        tr.dataset.anggaran  = anggaran;
        tr.dataset.realisasi = realisasi;
    }


    /* ════ 3. SUBMIT FORM EDIT DENGAN AJAX ════ */
    window.openEditModal = function (id, anggaran, realisasi, uraian, kode) {
        closeAllDropdowns();
        var form = document.getElementById('formEditNominal');
        form.action = '{{ url("admin/keuangan/input-template") }}/' + id;
        document.getElementById('edit_kode_rekening').value = kode;
        document.getElementById('edit_uraian').value        = uraian;
        document.getElementById('edit_anggaran').value      = anggaran;
        document.getElementById('edit_realisasi').value     = realisasi;
        openModal('modalEditNominal');
        setTimeout(function() { document.getElementById('edit_anggaran').focus(); }, 100);
    };

    document.getElementById('formEditNominal').addEventListener('submit', function (e) {
        e.preventDefault();
        var form         = this;
        var kode         = document.getElementById('edit_kode_rekening').value;
        var newAnggaran  = parseFloat(document.getElementById('edit_anggaran').value)  || 0;
        var newRealisasi = parseFloat(document.getElementById('edit_realisasi').value) || 0;
        var submitBtn    = form.querySelector('[type="submit"]');

        submitBtn.disabled = true; 
        submitBtn.textContent = 'Menyimpan…';

        fetch(form.action, {
            method : 'POST', 
            body   : new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            // Begitu sukses, langsung update UI tanpa refresh halaman
            recalcParents(kode, newAnggaran, newRealisasi);
            closeModal('modalEditNominal');
            
            // Tambahkan animasi flash kecil atau abaikan alert agar lebih mulus
            // alert('Nominal berhasil diperbarui.'); 
        })
        .catch(function(err) {
            alert('Gagal menyimpan via AJAX, mencoba metode standar...'); 
            form.submit();
        })
        .finally(function() {
            submitBtn.disabled = false; 
            submitBtn.textContent = 'Simpan Perubahan';
        });
    });


    /* ════ 4. SUBMIT FORM HAPUS ════ */
    window.openHapusModal = function (id, uraian) {
        closeAllDropdowns();
        document.getElementById('formHapus').action = '{{ url("admin/keuangan/input-template") }}/' + id;
        document.getElementById('hapus_uraian').textContent = uraian;
        openModal('modalHapus');
    };


    /* ════ 5. FITUR CHECK ALL (Berjalan per Kelompok/Tabel) ════ */
    document.querySelectorAll('.checkAllGroup').forEach(function(checkAllBtn) {
        checkAllBtn.addEventListener('change', function() {
            // Mencari tabel terdekat dari checkbox "Pilih Semua" ini
            var table = this.closest('table');
            if(table) {
                // Hanya centang baris yang ada di dalam tabel ini
                table.querySelectorAll('.cb-row').forEach(function(cb) {
                    cb.checked = checkAllBtn.checked;
                });
            }
        });
    });


    // Inisialisasi awal saat pertama kali buka web
    calculateAllParentsOnLoad();

})();
</script>

@endsection