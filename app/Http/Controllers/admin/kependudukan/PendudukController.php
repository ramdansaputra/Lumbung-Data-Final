<?php

namespace App\Http\Controllers\Admin\kependudukan;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Ref\RefAgama;
use App\Models\Ref\RefCaraKb;
use App\Models\Ref\RefGolonganDarah;
use App\Models\Ref\RefPekerjaan;
use App\Models\Ref\RefPendidikan;
use App\Models\Ref\RefShdk;
use App\Models\Ref\RefStatusKawin;
use App\Models\Ref\RefWarganegara;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class PendudukController extends Controller {
    // =========================================================================
    // KOLOM EXPORT / IMPORT
    // Diupdate sesuai kolom baru — kolom referensi pakai nama teks bukan ID
    // =========================================================================
    private array $exportColumns = [
        'nik'              => 'NIK',
        'nama'             => 'Nama Lengkap',
        'nama_ayah'        => 'Nama Ayah',
        'nama_ibu'         => 'Nama Ibu',
        'nik_ayah'         => 'NIK Ayah',
        'nik_ibu'          => 'NIK Ibu',
        'jenis_kelamin'    => 'Jenis Kelamin (L/P)',
        'tempat_lahir'     => 'Tempat Lahir',
        'tanggal_lahir'    => 'Tanggal Lahir (YYYY-MM-DD)',
        'agama_lama'       => 'Agama',
        'pendidikan_lama'  => 'Pendidikan',
        'pekerjaan_lama'   => 'Pekerjaan',
        'golongan_darah_lama' => 'Golongan Darah',
        'status_kawin_lama'   => 'Status Kawin',
        'status_dasar'     => 'Status Dasar (hidup/mati/pindah/hilang)',
        'jenis_tambah'     => 'Jenis Tambah (lahir/masuk)',
        'kewarganegaraan_lama' => 'Kewarganegaraan',
        'no_telp'          => 'No. Telepon',
        'email'            => 'Email',
        'alamat'           => 'Alamat',
        'tgl_peristiwa'    => 'Tanggal Peristiwa (YYYY-MM-DD)',
        'tgl_terdaftar'    => 'Tanggal Terdaftar (YYYY-MM-DD)',
    ];

    // =========================================================================
    // INDEX
    // =========================================================================
    public function index(Request $request) {
        $query = Penduduk::with(['wilayah', 'keluarga', 'agama', 'pekerjaan', 'shdk']);

        // ── Filter: Status Dasar (default: hidup) ─────────────────────────────
        $statusDasar = $request->get('status_dasar', 'hidup');
        if ($statusDasar && $statusDasar !== 'semua') {
            $query->where('status_dasar', $statusDasar);
        }

        // ── Filter: Status (jenis penduduk) ───────────────────────────────────
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // ── Filter: Jenis Tambah ───────────────────────────────────────────────
        if ($request->filled('jenis_tambah') && $request->jenis_tambah !== 'semua') {
            $query->where('jenis_tambah', $request->jenis_tambah);
        }

        // ── Filter: Jenis Kelamin ─────────────────────────────────────────────
        if ($request->filled('jenis_kelamin') && $request->jenis_kelamin !== 'semua') {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // ── Filter: Wilayah / Dusun ───────────────────────────────────────────
        if ($request->filled('wilayah_id')) {
            $query->where('wilayah_id', $request->wilayah_id);
        } elseif ($request->filled('dusun')) {
            $query->whereHas('wilayah', fn($q) => $q->where('dusun', $request->dusun));
        }

        // ── Search: nama, NIK, nama ayah, nama ibu ────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('nama_ayah', 'like', "%{$search}%")
                    ->orWhere('nama_ibu', 'like', "%{$search}%");
            });
        }

        // ── Filter: NIK Sementara ─────────────────────────────────────────────
        // Kolom is_nik_sementara ditambahkan via migration (jalankan: php artisan migrate).
        // Fallback: jika kolom belum ada di DB, skip filter agar tidak error 500.
        if ($request->boolean('nik_sementara')) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('penduduk', 'is_nik_sementara')) {
                $query->where('is_nik_sementara', true);
            }
        }

        // ── Pencarian Spesifik: Umur ──────────────────────────────────────────
        if ($request->filled('umur_dari') || $request->filled('umur_sampai')) {
            $satuan  = $request->get('umur_satuan', 'tahun') === 'bulan' ? 'MONTH' : 'YEAR';
            $fn      = "TIMESTAMPDIFF({$satuan}, tanggal_lahir, CURDATE())";
            if ($request->filled('umur_dari'))
                $query->whereRaw("{$fn} >= ?", [(int) $request->umur_dari]);
            if ($request->filled('umur_sampai'))
                $query->whereRaw("{$fn} <= ?", [(int) $request->umur_sampai]);
        }

        // ── Pencarian Spesifik: Tanggal Lahir ────────────────────────────────
        if ($request->filled('tanggal_lahir')) {
            $tgl = $request->tanggal_lahir;
            // Format YYYY-MM-DD → exact match; format MM-DD → match bulan & hari
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)) {
                $query->whereDate('tanggal_lahir', $tgl);
            } elseif (preg_match('/^(\d{2})-(\d{2})$/', $tgl, $m)) {
                $query->whereMonth('tanggal_lahir', (int)$m[1])->whereDay('tanggal_lahir', (int)$m[2]);
            }
        }

        // ── Pencarian Spesifik: Referensi ────────────────────────────────────
        foreach (
            [
                'pekerjaan_id'       => 'pekerjaan_id',
                'status_kawin_id'    => 'status_kawin_id',
                'agama_id'           => 'agama_id',
                'pendidikan_kk_id'   => 'pendidikan_kk_id',
                'golongan_darah_id'  => 'golongan_darah_id',
                'cara_kb_id'         => 'cara_kb_id',
                'warganegara_id'     => 'warganegara_id',
            ] as $param => $col
        ) {
            if ($request->filled($param)) {
                $query->where($col, $request->$param);
            }
        }

        // ── Pencarian Spesifik: Boolean flags ────────────────────────────────
        if ($request->filled('disabilitas'))
            $query->where('disabilitas', $request->disabilitas === 'ya' ? 1 : 0);

        if ($request->filled('asuransi'))
            $query->where('asuransi', $request->asuransi === 'ya' ? 1 : 0);

        if ($request->filled('bpjs_ketenagakerjaan'))
            $query->where('bpjs_ketenagakerjaan', $request->bpjs_ketenagakerjaan === 'ya' ? 1 : 0);

        if ($request->filled('sakit_menahun'))
            $query->where('sakit_menahun', $request->sakit_menahun === 'ya' ? 1 : 0);

        if ($request->filled('status_ktp'))
            $query->where('status_ktp', $request->status_ktp);

        if ($request->filled('has_tag_id_card'))
            $query->where('tag_id_card', $request->has_tag_id_card === 'ya' ? '!=' : '=', null);

        if ($request->filled('has_kk'))
            $query->when(
                $request->has_kk === 'ya',
                fn($q) => $q->whereNotNull('keluarga_id'),
                fn($q) => $q->whereNull('keluarga_id')
            );

        // ── Pencarian Spesifik: Text bebas ───────────────────────────────────
        if ($request->filled('adat'))       $query->where('adat', 'like', '%' . $request->adat . '%');
        if ($request->filled('suku_etnis')) $query->where('suku_etnis', 'like', '%' . $request->suku_etnis . '%');
        if ($request->filled('marga'))      $query->where('marga', 'like', '%' . $request->marga . '%');

        // ── Program Bantuan ───────────────────────────────────────────────────
        if ($request->filled('program_bantuan_id') && class_exists(\App\Models\BantuanPeserta::class)) {
            $query->whereHas('bantuanPeserta', fn($q) => $q->where('bantuan_id', $request->program_bantuan_id));
        }

        // ── Kumpulan NIK ──────────────────────────────────────────────────────
        if ($request->filled('kumpulan_nik')) {
            $niks = preg_split('/[\s,;]+/', trim($request->kumpulan_nik), -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($niks)) {
                $query->whereIn('nik', $niks);
            }
        }

        $perPage  = (int) $request->get('per_page', 10);
        $penduduk = $query->latest('tgl_terdaftar')->paginate($perPage)->appends($request->query());

        // ── Stats ─────────────────────────────────────────────────────────────
        $total_penduduk = Penduduk::wargaAktif()->count();
        $laki_laki      = Penduduk::wargaAktif()->where('jenis_kelamin', 'L')->count();
        $perempuan      = Penduduk::wargaAktif()->where('jenis_kelamin', 'P')->count();
        $keluarga       = Keluarga::aktif()->count();

        // ── Dropdown filter ───────────────────────────────────────────────────
        $dusunList  = Wilayah::orderBy('dusun')->pluck('dusun')->unique()->filter()->values();
        $wilayahList = Wilayah::orderBy('dusun')->orderBy('rw')->orderBy('rt')->get();

        // ── Refs untuk modal pencarian spesifik ───────────────────────────────
        $refAgama           = \App\Models\Ref\RefAgama::orderBy('nama')->get();
        $refPekerjaan       = \App\Models\Ref\RefPekerjaan::orderBy('nama')->get();
        $refPendidikan      = \App\Models\Ref\RefPendidikan::orderBy('id')->get();
        $refStatusKawin     = \App\Models\Ref\RefStatusKawin::orderBy('id')->get();
        $refGolDarah        = \App\Models\Ref\RefGolonganDarah::orderBy('nama')->get();
        $refCaraKb          = \App\Models\Ref\RefCaraKb::orderBy('id')->get();
        $refWarganegara     = \App\Models\Ref\RefWarganegara::orderBy('id')->get();
        $programBantuanList = class_exists(\App\Models\Bantuan::class)
            ? \App\Models\Bantuan::orderBy('nama')->get()
            : collect();

        return view('admin.penduduk', compact(
            'penduduk',
            'total_penduduk',
            'laki_laki',
            'perempuan',
            'keluarga',
            'dusunList',
            'wilayahList',
            'refAgama',
            'refPekerjaan',
            'refPendidikan',
            'refStatusKawin',
            'refGolDarah',
            'refCaraKb',
            'refWarganegara',
            'programBantuanList',
        ));
    }

    // =========================================================================
    // CREATE
    // =========================================================================
    public function create(Request $request) {
        $jenis = in_array($request->get('jenis'), ['lahir', 'masuk'])
            ? $request->get('jenis')
            : 'lahir'; // 'meninggal' dihapus dari jenis_tambah

        $keluarga = Keluarga::aktif()
            ->with('kepalaKeluarga:id,nama')
            ->select('id', 'no_kk', 'kepala_keluarga_id', 'alamat')
            ->get();

        $wilayah = Wilayah::select('id', 'rt', 'rw', 'dusun')
            ->orderBy('dusun')->orderBy('rw')->orderBy('rt')
            ->get();

        // Referensi dari tabel master
        $refAgama       = RefAgama::orderBy('nama')->get();
        $refGolDarah    = RefGolonganDarah::orderBy('nama')->get();
        $refStatusKawin = RefStatusKawin::orderBy('id')->get();
        $refPendidikan  = RefPendidikan::orderBy('id')->get();
        $refPekerjaan   = RefPekerjaan::orderBy('nama')->get();
        $refShdk        = RefShdk::orderBy('id')->get();
        $refWarganegara = RefWarganegara::orderBy('id')->get();
        $refCaraKb      = RefCaraKb::orderBy('id')->get();

        return view('admin.penduduk-create', compact(
            'jenis',
            'keluarga',
            'wilayah',
            'refAgama',
            'refGolDarah',
            'refStatusKawin',
            'refPendidikan',
            'refPekerjaan',
            'refShdk',
            'refWarganegara',
            'refCaraKb',
        ));
    }

    // =========================================================================
    // STORE
    // =========================================================================
    public function store(Request $request) {
        $validated = $request->validate([
            'nik'                   => 'required|string|max:16|unique:penduduk,nik',
            'foto'                  => 'nullable|image|max:2048',
            'tag_id_card'           => 'nullable|string|max:255',
            'nama'                  => 'required|string|max:255',
            'nama_ayah'             => 'nullable|string|max:255',
            'nama_ibu'              => 'required|string|max:255',
            'nik_ayah'              => 'nullable|string|max:16',
            'nik_ibu'               => 'nullable|string|max:16',
            'wilayah_id'            => 'required|exists:wilayah,id',
            'keluarga_id'           => 'nullable|exists:keluarga,id',
            'kk_level'              => 'nullable|exists:ref_shdk,id',
            'jenis_kelamin'         => 'required|in:L,P',
            'tempat_lahir'          => 'required|string|max:255',
            'tanggal_lahir'         => 'required|date',
            'agama_id'              => 'nullable|exists:ref_agama,id',
            'pendidikan_kk_id'      => 'nullable|exists:ref_pendidikan,id',
            'pendidikan_sedang_id'  => 'nullable|exists:ref_pendidikan,id',
            'pekerjaan_id'          => 'nullable|exists:ref_pekerjaan,id',
            'golongan_darah_id'     => 'nullable|exists:ref_golongan_darah,id',
            'status_kawin_id'       => 'required|exists:ref_status_kawin,id',
            'warganegara_id'        => 'required|exists:ref_warganegara,id',
            'status'                => 'nullable|in:1,2,3',
            'status_dasar'          => 'nullable|in:hidup,mati,pindah,hilang',
            'jenis_tambah'          => 'required|in:lahir,masuk', // 'meninggal' dihapus
            'akta_perkawinan'       => 'nullable|string|max:100',
            'tanggal_perkawinan'    => 'nullable|date',
            'akta_perceraian'       => 'nullable|string|max:100',
            'tanggal_perceraian'    => 'nullable|date',
            'akta_lahir'            => 'nullable|string|max:100',
            'ktp_el'                => 'nullable|boolean',
            'tgl_peristiwa'         => 'nullable|date',
            'tgl_terdaftar'         => 'nullable|date',
            'no_telp'               => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'alamat'                => 'nullable|string',
            'alamat_sebelumnya'     => 'nullable|string',
            'no_kk_sebelumnya'      => 'nullable|string|max:16',
            'keterangan'            => 'nullable|string',
        ]);

        // ── Upload foto ────────────────────────────────────────────────────────
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('penduduk/foto', 'public');
        }

        // ── Default values ─────────────────────────────────────────────────────
        $validated['status']       ??= Penduduk::STATUS_TETAP;
        $validated['status_dasar'] ??= Penduduk::STATUS_DASAR_HIDUP;
        $validated['tgl_terdaftar'] ??= now()->toDateString();

        // ── Jika masuk ke KK baru sebagai KK → set kepala_keluarga_id di KK ──
        $penduduk = Penduduk::create($validated);

        if ($penduduk->keluarga_id && $penduduk->kk_level == Penduduk::SHDK_KEPALA_KELUARGA) {
            Keluarga::where('id', $penduduk->keluarga_id)->update([
                'kepala_keluarga_id' => $penduduk->id,
                'nik_kepala'         => $penduduk->nik,
            ]);
        }

        return redirect()->route('admin.penduduk')
            ->with('success', 'Penduduk berhasil ditambahkan.');
    }

    // =========================================================================
    // SHOW
    // =========================================================================
    public function show(Penduduk $penduduk) {
        $penduduk->load([
            'wilayah',
            'keluarga.wilayah',
            'keluarga.kepalaKeluarga',
            'agama',
            'pekerjaan',
            'pendidikanKk',
            'pendidikanSedang',
            'golonganDarah',
            'statusKawin',
            'warganegara',
            'shdk',
            'cacat',
            'sakitMenahun',
            'caraKb',
            'asuransi',
            'ayah',
            'ibu',
            'user',
        ]);

        return view('admin.penduduk-show', compact('penduduk'));
    }

    // =========================================================================
    // EDIT
    // =========================================================================
    public function edit(Penduduk $penduduk) {
        $keluarga = Keluarga::aktif()
            ->with('kepalaKeluarga:id,nama')
            ->select('id', 'no_kk', 'kepala_keluarga_id', 'alamat')
            ->get();

        $wilayah = Wilayah::select('id', 'rt', 'rw', 'dusun')
            ->orderBy('dusun')->orderBy('rw')->orderBy('rt')
            ->get();

        $refAgama       = RefAgama::orderBy('nama')->get();
        $refGolDarah    = RefGolonganDarah::orderBy('nama')->get();
        $refStatusKawin = RefStatusKawin::orderBy('id')->get();
        $refPendidikan  = RefPendidikan::orderBy('id')->get();
        $refPekerjaan   = RefPekerjaan::orderBy('nama')->get();
        $refShdk        = RefShdk::orderBy('id')->get();
        $refWarganegara = RefWarganegara::orderBy('id')->get();
        $refCaraKb      = RefCaraKb::orderBy('id')->get();

        return view('admin.penduduk-edit', compact(
            'penduduk',
            'keluarga',
            'wilayah',
            'refAgama',
            'refGolDarah',
            'refStatusKawin',
            'refPendidikan',
            'refPekerjaan',
            'refShdk',
            'refWarganegara',
            'refCaraKb',
        ));
    }

    // =========================================================================
    // UPDATE
    // =========================================================================
    public function update(Request $request, Penduduk $penduduk) {
        $validated = $request->validate([
            'nik'                   => 'required|string|max:16|unique:penduduk,nik,' . $penduduk->id,
            'foto'                  => 'nullable|image|max:2048',
            'tag_id_card'           => 'nullable|string|max:255',
            'nama'                  => 'required|string|max:255',
            'nama_ayah'             => 'nullable|string|max:255',
            'nama_ibu'              => 'required|string|max:255',
            'nik_ayah'              => 'nullable|string|max:16',
            'nik_ibu'               => 'nullable|string|max:16',
            'wilayah_id'            => 'required|exists:wilayah,id',
            'keluarga_id'           => 'nullable|exists:keluarga,id',
            'kk_level'              => 'nullable|exists:ref_shdk,id',
            'jenis_kelamin'         => 'required|in:L,P',
            'tempat_lahir'          => 'required|string|max:255',
            'tanggal_lahir'         => 'required|date',
            'agama_id'              => 'nullable|exists:ref_agama,id',
            'pendidikan_kk_id'      => 'nullable|exists:ref_pendidikan,id',
            'pendidikan_sedang_id'  => 'nullable|exists:ref_pendidikan,id',
            'pekerjaan_id'          => 'nullable|exists:ref_pekerjaan,id',
            'golongan_darah_id'     => 'nullable|exists:ref_golongan_darah,id',
            'status_kawin_id'       => 'required|exists:ref_status_kawin,id',
            'warganegara_id'        => 'required|exists:ref_warganegara,id',
            'status'                => 'nullable|in:1,2,3',
            'status_dasar'          => 'nullable|in:hidup,mati,pindah,hilang',
            'jenis_tambah'          => 'required|in:lahir,masuk',
            'akta_perkawinan'       => 'nullable|string|max:100',
            'tanggal_perkawinan'    => 'nullable|date',
            'akta_perceraian'       => 'nullable|string|max:100',
            'tanggal_perceraian'    => 'nullable|date',
            'akta_lahir'            => 'nullable|string|max:100',
            'ktp_el'                => 'nullable|boolean',
            'tgl_peristiwa'         => 'nullable|date',
            'tgl_terdaftar'         => 'nullable|date',
            'no_telp'               => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'alamat'                => 'nullable|string',
            'alamat_sebelumnya'     => 'nullable|string',
            'no_kk_sebelumnya'      => 'nullable|string|max:16',
            'keterangan'            => 'nullable|string',
        ]);

        // ── Upload foto baru & hapus yang lama ────────────────────────────────
        if ($request->hasFile('foto')) {
            if ($penduduk->foto) {
                Storage::disk('public')->delete($penduduk->foto);
            }
            $validated['foto'] = $request->file('foto')->store('penduduk/foto', 'public');
        }

        $oldKeluargaId  = $penduduk->keluarga_id;
        $oldKkLevel     = $penduduk->kk_level;

        $penduduk->update($validated);

        // ── Sinkronisasi kepala_keluarga_id di tabel keluarga ─────────────────
        // Jika SHDK berubah jadi KK di KK baru
        if (
            $penduduk->keluarga_id
            && $penduduk->kk_level == Penduduk::SHDK_KEPALA_KELUARGA
            && ($oldKeluargaId != $penduduk->keluarga_id || $oldKkLevel != $penduduk->kk_level)
        ) {
            Keluarga::where('id', $penduduk->keluarga_id)->update([
                'kepala_keluarga_id' => $penduduk->id,
                'nik_kepala'         => $penduduk->nik,
            ]);
        }

        // Jika SHDK diubah dari KK → bukan KK, kosongkan kepala_keluarga_id di KK lama
        if (
            $oldKkLevel == Penduduk::SHDK_KEPALA_KELUARGA
            && $penduduk->kk_level != Penduduk::SHDK_KEPALA_KELUARGA
            && $oldKeluargaId
        ) {
            Keluarga::where('id', $oldKeluargaId)
                ->where('kepala_keluarga_id', $penduduk->id)
                ->update(['kepala_keluarga_id' => null, 'nik_kepala' => null]);
        }

        return redirect()->route('admin.penduduk')
            ->with('success', 'Penduduk berhasil diperbarui.');
    }

    // =========================================================================
    // UBAH STATUS DASAR (Pindah / Meninggal / Hilang)
    // Di OpenSID ini fitur terpisah dari edit biodata
    // =========================================================================
    public function ubahStatusDasar(Request $request, Penduduk $penduduk) {
        $request->validate([
            'status_dasar'  => 'required|in:mati,pindah,hilang',
            'tgl_peristiwa' => 'required|date',
            'keterangan'    => 'nullable|string',
        ]);

        // KK tidak bisa langsung diubah status menjadi mati
        // sesuai aturan OpenSID — harus pindah KK dulu
        if (
            $penduduk->kk_level == Penduduk::SHDK_KEPALA_KELUARGA
            && $request->status_dasar === 'mati'
        ) {
            return back()->with('error', 'Kepala Keluarga tidak bisa langsung diubah statusnya menjadi meninggal. Tetapkan Kepala Keluarga baru terlebih dahulu.');
        }

        $penduduk->update([
            'status_dasar'  => $request->status_dasar,
            'tgl_peristiwa' => $request->tgl_peristiwa,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect()->route('admin.penduduk')
            ->with('success', "Status penduduk {$penduduk->nama} berhasil diubah menjadi {$penduduk->label_status_dasar}.");
    }

    // =========================================================================
    // DESTROY (SoftDelete)
    // =========================================================================
    public function confirmDestroy(Penduduk $penduduk) {
        return view('admin.penduduk-delete', compact('penduduk'));
    }

    public function destroy(Penduduk $penduduk) {
        // Jika penduduk ini adalah kepala KK, kosongkan kepala_keluarga_id
        if ($penduduk->kk_level == Penduduk::SHDK_KEPALA_KELUARGA && $penduduk->keluarga_id) {
            Keluarga::where('id', $penduduk->keluarga_id)
                ->where('kepala_keluarga_id', $penduduk->id)
                ->update(['kepala_keluarga_id' => null, 'nik_kepala' => null]);
        }

        if ($penduduk->foto) {
            Storage::disk('public')->delete($penduduk->foto);
        }

        $penduduk->delete(); // SoftDelete

        return redirect()->route('admin.penduduk')
            ->with('success', 'Penduduk berhasil dihapus.');
    }

    // =========================================================================
    // BULK DESTROY
    // =========================================================================
    public function bulkDestroy(Request $request) {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:penduduk,id',
        ]);

        $penduduks = Penduduk::whereIn('id', $request->ids)->get();
        $count     = $penduduks->count();

        foreach ($penduduks as $p) {
            if ($p->kk_level == Penduduk::SHDK_KEPALA_KELUARGA && $p->keluarga_id) {
                Keluarga::where('id', $p->keluarga_id)
                    ->where('kepala_keluarga_id', $p->id)
                    ->update(['kepala_keluarga_id' => null, 'nik_kepala' => null]);
            }
            if ($p->foto) {
                Storage::disk('public')->delete($p->foto);
            }
            $p->delete();
        }

        return redirect()->route('admin.penduduk')
            ->with('success', "{$count} penduduk berhasil dihapus.");
    }

    // =========================================================================
    // DOWNLOAD TEMPLATE IMPORT
    // =========================================================================
    public function downloadTemplate() {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet()->setTitle('Template');

        $col = 'A';
        foreach ($this->exportColumns as $field => $label) {
            $sheet->setCellValue($col . '1', $label);
            $col++;
        }
        $lastCol = chr(ord('A') + count($this->exportColumns) - 1);
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);

        $example = [
            '3302011234560001',
            'Budi Santoso',
            'Slamet',
            'Sri Wahyuni',
            '',
            '',
            'L',
            'Purwokerto',
            '1990-05-15',
            'ISLAM',
            'TAMAT SD/SEDERAJAT',
            'WIRASWASTA',
            'A',
            'KAWIN TERCATAT',
            'hidup',
            'lahir',
            'WNI',
            '081234567890',
            'budi@email.com',
            'Jl. Merdeka No. 10',
            '',
            '1990-05-15',
        ];
        $col = 'A';
        foreach ($example as $val) {
            $sheet->setCellValue($col . '2', $val);
            $col++;
        }
        $sheet->getStyle("A2:{$lastCol}2")->getFont()->setItalic(true)->getColor()->setRGB('6B7280');
        foreach (range('A', $lastCol) as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        // ── Sheet Referensi ────────────────────────────────────────────────────
        $refSheet = $spreadsheet->createSheet()->setTitle('Referensi');
        $refs = [
            'Jenis Kelamin'   => ['L', 'P'],
            'Agama'           => RefAgama::pluck('nama')->toArray(),
            'Golongan Darah'  => RefGolonganDarah::pluck('nama')->toArray(),
            'Status Kawin'    => RefStatusKawin::pluck('nama')->toArray(),
            'Pendidikan'      => RefPendidikan::pluck('nama')->toArray(),
            'Pekerjaan'       => RefPekerjaan::pluck('nama')->toArray(),
            'Status Dasar'    => ['hidup', 'mati', 'pindah', 'hilang'],
            'Jenis Tambah'    => ['lahir', 'masuk'],
            'Kewarganegaraan' => ['WNI', 'WNA', 'DWIKEWARGANEGARAAN'],
        ];
        $startCol = 'A';
        foreach ($refs as $kategori => $vals) {
            $refSheet->setCellValue($startCol . '1', $kategori);
            $refSheet->getStyle($startCol . '1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']],
            ]);
            foreach ($vals as $i => $v) {
                $refSheet->setCellValue($startCol . ($i + 2), $v);
            }
            $refSheet->getColumnDimension($startCol)->setAutoSize(true);
            $startCol++;
        }

        $writer = new XlsxWriter($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'template_import_penduduk.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // =========================================================================
    // IMPORT
    // =========================================================================
    public function import(Request $request) {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xls,xlsx', 'max:10240'],
            'mode' => ['required', 'in:skip,overwrite'],
        ]);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file')->getRealPath());
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, true);
        $highestRow  = $sheet->getHighestRow();

        // Mapping label kolom → field
        $labelToField = array_flip(array_map('strtolower', $this->exportColumns));
        $colMap       = [];
        foreach (($rows[1] ?? []) as $colLetter => $label) {
            $field = $labelToField[strtolower(trim((string) $label))] ?? null;
            if ($field) $colMap[$colLetter] = $field;
        }

        // Cache tabel referensi untuk mapping nama → id
        $agamaMap       = RefAgama::pluck('id', 'nama')->mapWithKeys(fn($v, $k) => [strtoupper($k) => $v]);
        $pekerjaanMap   = RefPekerjaan::pluck('id', 'nama')->mapWithKeys(fn($v, $k) => [strtoupper($k) => $v]);
        $pendidikanMap  = RefPendidikan::pluck('id', 'nama')->mapWithKeys(fn($v, $k) => [strtoupper($k) => $v]);
        $statusKawinMap = RefStatusKawin::pluck('id', 'nama')->mapWithKeys(fn($v, $k) => [strtoupper($k) => $v]);
        $golDarahMap    = RefGolonganDarah::pluck('id', 'nama')->mapWithKeys(fn($v, $k) => [strtoupper($k) => $v]);
        $warganegaraMap = RefWarganegara::pluck('id', 'nama')->mapWithKeys(fn($v, $k) => [strtoupper($k) => $v]);

        $imported = $skipped = 0;
        $importErrors = [];

        DB::beginTransaction();
        try {
            for ($rowNum = 2; $rowNum <= $highestRow; $rowNum++) {
                $rawRow = $rows[$rowNum] ?? [];
                $data   = [];

                foreach ($colMap as $letter => $field) {
                    $data[$field] = trim((string) ($rawRow[$letter] ?? ''));
                }

                if (empty($data['nik']) && empty($data['nama'])) continue;

                if (!preg_match('/^\d{16}$/', $data['nik'] ?? '')) {
                    $importErrors[] = "Baris {$rowNum}: NIK tidak valid — \"{$data['nik']}\"";
                    continue;
                }

                // Parse tanggal
                foreach (['tanggal_lahir', 'tgl_peristiwa', 'tgl_terdaftar'] as $f) {
                    if (!empty($data[$f])) {
                        try {
                            $data[$f] = Carbon::parse($data[$f])->format('Y-m-d');
                        } catch (\Exception) {
                            unset($data[$f]);
                        }
                    }
                }

                // Normalisasi
                $data['jenis_kelamin'] = strtoupper($data['jenis_kelamin'] ?? 'L');
                if (!in_array($data['jenis_kelamin'], ['L', 'P'])) $data['jenis_kelamin'] = 'L';
                if (!in_array($data['jenis_tambah'] ?? '', ['lahir', 'masuk'])) $data['jenis_tambah'] = 'lahir';
                if (!in_array($data['status_dasar'] ?? '', ['hidup', 'mati', 'pindah', 'hilang'])) $data['status_dasar'] = 'hidup';

                // Map nama referensi → id
                if (!empty($data['agama_lama'])) {
                    $data['agama_id'] = $agamaMap[strtoupper($data['agama_lama'])] ?? null;
                }
                if (!empty($data['pekerjaan_lama'])) {
                    $data['pekerjaan_id'] = $pekerjaanMap[strtoupper($data['pekerjaan_lama'])] ?? null;
                }
                if (!empty($data['pendidikan_lama'])) {
                    $data['pendidikan_kk_id'] = $pendidikanMap[strtoupper($data['pendidikan_lama'])] ?? null;
                }
                if (!empty($data['status_kawin_lama'])) {
                    $data['status_kawin_id'] = $statusKawinMap[strtoupper($data['status_kawin_lama'])] ?? null;
                }
                if (!empty($data['golongan_darah_lama'])) {
                    $data['golongan_darah_id'] = $golDarahMap[strtoupper($data['golongan_darah_lama'])] ?? null;
                }
                if (!empty($data['kewarganegaraan_lama'])) {
                    $data['warganegara_id'] = $warganegaraMap[strtoupper($data['kewarganegaraan_lama'])] ?? null;
                }

                $data = array_intersect_key($data, array_flip((new Penduduk)->getFillable()));
                $data['tgl_terdaftar'] ??= now()->toDateString();
                $data['status']        ??= Penduduk::STATUS_TETAP;
                $data['status_dasar']  ??= Penduduk::STATUS_DASAR_HIDUP;

                $existing = Penduduk::where('nik', $data['nik'])->first();
                if ($existing) {
                    if ($request->mode === 'overwrite') {
                        $existing->update($data);
                        $imported++;
                    } else {
                        $skipped++;
                    }
                } else {
                    Penduduk::create($data);
                    $imported++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }

        $msg = "{$imported} data berhasil diimport";
        if ($skipped) $msg .= ", {$skipped} duplikat dilewati";
        if ($importErrors) $msg .= ', ' . count($importErrors) . ' baris gagal';

        return back()->with('success', $msg)->with('import_errors', $importErrors);
    }

    // =========================================================================
    // EXPORT EXCEL
    // =========================================================================
    public function exportExcel(Request $request) {
        $data = $this->buildExportQuery($request)->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet()->setTitle('Data Penduduk');

        $headers = array_merge(['No'], array_values($this->exportColumns));
        $col     = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col++ . '1', $h);
        }
        $lastCol = chr(ord('A') + count($headers) - 1);
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->freezePane('A2');

        foreach ($data as $i => $row) {
            $rowNum = $i + 2;
            $colIdx = 'A';
            $sheet->setCellValue($colIdx++ . $rowNum, $i + 1);
            foreach (array_keys($this->exportColumns) as $field) {
                $val = in_array($field, ['tanggal_lahir', 'tgl_peristiwa', 'tgl_terdaftar'])
                    ? optional($row->$field)->format('d/m/Y')
                    : ($row->$field ?? '-');
                $sheet->setCellValue($colIdx++ . $rowNum, $val);
            }
            if ($i % 2 === 1) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F9FAFB');
            }
        }

        foreach (range('A', $lastCol) as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'data_penduduk_' . now()->format('Ymd_His') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // =========================================================================
    // EXPORT PDF
    // =========================================================================
    public function exportPdf(Request $request) {
        $penduduk = $this->buildExportQuery($request)->get();
        $stats = [
            'total'     => $penduduk->count(),
            'laki_laki' => $penduduk->where('jenis_kelamin', 'L')->count(),
            'perempuan' => $penduduk->where('jenis_kelamin', 'P')->count(),
        ];

        $pdf = Pdf::loadView('admin.penduduk-export-pdf', compact('penduduk', 'stats'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['dpi' => 110, 'defaultFont' => 'sans-serif']);

        return $pdf->download('data_penduduk_' . now()->format('Ymd_His') . '.pdf');
    }

    // =========================================================================
    // PENCARIAN SPESIFIK
    // Cari penduduk berdasarkan kombinasi atribut spesifik (bukan keyword bebas)
    // =========================================================================
    public function pencarianSpesifik(Request $request) {
        $dusunList      = Wilayah::orderBy('dusun')->pluck('dusun')->unique()->filter()->values();
        $refAgama       = \App\Models\Ref\RefAgama::orderBy('nama')->get();
        $refPekerjaan   = \App\Models\Ref\RefPekerjaan::orderBy('nama')->get();
        $refPendidikan  = \App\Models\Ref\RefPendidikan::orderBy('id')->get();
        $refStatusKawin = \App\Models\Ref\RefStatusKawin::orderBy('id')->get();

        $penduduk = null;
        if ($request->isMethod('POST') || $request->anyFilled([
            'nama',
            'tempat_lahir',
            'tanggal_lahir_dari',
            'tanggal_lahir_sampai',
            'jenis_kelamin',
            'agama_id',
            'pekerjaan_id',
            'pendidikan_id',
            'status_kawin_id',
            'dusun',
            'umur_dari',
            'umur_sampai',
        ])) {
            $query = Penduduk::with(['wilayah', 'keluarga', 'agama', 'pekerjaan']);

            if ($request->filled('nama'))
                $query->where('nama', 'like', '%' . $request->nama . '%');
            if ($request->filled('tempat_lahir'))
                $query->where('tempat_lahir', 'like', '%' . $request->tempat_lahir . '%');
            if ($request->filled('tanggal_lahir_dari'))
                $query->whereDate('tanggal_lahir', '>=', $request->tanggal_lahir_dari);
            if ($request->filled('tanggal_lahir_sampai'))
                $query->whereDate('tanggal_lahir', '<=', $request->tanggal_lahir_sampai);
            if ($request->filled('jenis_kelamin'))
                $query->where('jenis_kelamin', $request->jenis_kelamin);
            if ($request->filled('agama_id'))
                $query->where('agama_id', $request->agama_id);
            if ($request->filled('pekerjaan_id'))
                $query->where('pekerjaan_id', $request->pekerjaan_id);
            if ($request->filled('pendidikan_id'))
                $query->where('pendidikan_kk_id', $request->pendidikan_id);
            if ($request->filled('status_kawin_id'))
                $query->where('status_kawin_id', $request->status_kawin_id);
            if ($request->filled('dusun'))
                $query->whereHas('wilayah', fn($q) => $q->where('dusun', $request->dusun));
            if ($request->filled('umur_dari'))
                $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= ?', [$request->umur_dari]);
            if ($request->filled('umur_sampai'))
                $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= ?', [$request->umur_sampai]);

            $penduduk = $query->where('status_dasar', 'hidup')
                ->orderBy('nama')
                ->paginate(25)
                ->appends($request->query());
        }

        return view('admin.penduduk-pencarian-spesifik', compact(
            'penduduk',
            'dusunList',
            'refAgama',
            'refPekerjaan',
            'refPendidikan',
            'refStatusKawin'
        ));
    }

    public function pencarianSpesifikStore(Request $request) {
        return $this->pencarianSpesifik($request);
    }

    // =========================================================================
    // PROGRAM BANTUAN — filter penduduk berdasarkan program bantuan sosial
    // =========================================================================
    public function programBantuan(Request $request) {
        // Ambil daftar program bantuan dari tabel bantuans jika ada
        $programList = [];
        if (class_exists(\App\Models\Bantuan::class)) {
            $programList = \App\Models\Bantuan::orderBy('nama')->get();
        }

        $penduduk = null;
        if ($request->filled('program_id')) {
            if (class_exists(\App\Models\BantuanPeserta::class)) {
                $penduduk = Penduduk::with(['wilayah', 'keluarga'])
                    ->whereHas('bantuanPeserta', fn($q) => $q->where('bantuan_id', $request->program_id))
                    ->where('status_dasar', 'hidup')
                    ->orderBy('nama')
                    ->paginate(25)
                    ->appends($request->query());
            }
        }

        return view('admin.penduduk-program-bantuan', compact('programList', 'penduduk'));
    }

    // =========================================================================
    // KUMPULAN NIK — masukkan daftar NIK manual, tampilkan datanya
    // =========================================================================
    public function kumpulanNik(Request $request) {
        $penduduk = null;

        if ($request->filled('nik_list')) {
            // Terima NIK dipisahkan newline, koma, atau spasi
            $niks = preg_split('/[\s,;]+/', trim($request->nik_list), -1, PREG_SPLIT_NO_EMPTY);
            $niks = array_filter(array_map('trim', $niks));

            if (!empty($niks)) {
                $penduduk = Penduduk::with(['wilayah', 'keluarga'])
                    ->whereIn('nik', $niks)
                    ->orderBy('nama')
                    ->paginate(50)
                    ->appends($request->query());
            }
        }

        return view('admin.penduduk-kumpulan-nik', compact('penduduk'));
    }

    // =========================================================================
    // LOKASI TEMPAT TINGGAL — tampilkan peta berdasarkan koordinat wilayah
    // =========================================================================
    public function lokasi(Penduduk $penduduk) {
        $penduduk->load(['wilayah', 'keluarga']);

        // Helper: cek apakah koordinat valid untuk Indonesia
        $isValidIndonesia = function (?float $lat, ?float $lng): bool {
            if ($lat === null || $lng === null) return false;
            return $lat >= -11 && $lat <= 6 && $lng >= 95 && $lng <= 141;
        };

        // Prioritas: koordinat penduduk sendiri → koordinat wilayah → null
        $pendudukLat = $penduduk->lat ? (float)$penduduk->lat : null;
        $pendudukLng = $penduduk->lng ? (float)$penduduk->lng : null;

        $wilayahLat = $penduduk->wilayah?->lat ? (float)$penduduk->wilayah->lat : null;
        $wilayahLng = $penduduk->wilayah?->lng ? (float)$penduduk->wilayah->lng : null;

        // Hanya pakai koordinat wilayah jika valid
        if (!$isValidIndonesia($wilayahLat, $wilayahLng)) {
            $wilayahLat = null;
            $wilayahLng = null;
        }

        $lat = $isValidIndonesia($pendudukLat, $pendudukLng) ? $pendudukLat : null;
        $lng = $isValidIndonesia($pendudukLat, $pendudukLng) ? $pendudukLng : null;

        return view('admin.penduduk-lokasi', compact('penduduk', 'lat', 'lng', 'wilayahLat', 'wilayahLng'));
    }

    public function lokasiStore(Request $request, Penduduk $penduduk) {
        $request->validate([
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
        $penduduk->update([
            'lat' => $request->latitude ?: null,  // ✅ simpan ke kolom lat
            'lng' => $request->longitude ?: null, // ✅ simpan ke kolom lng
        ]);
        return redirect()->route('admin.penduduk.lokasi', $penduduk)
            ->with('success', 'Koordinat lokasi berhasil disimpan.');
    }

    // =========================================================================
    // DOKUMEN PENDUDUK — list & upload dokumen pendukung
    // =========================================================================
    public function dokumen(Penduduk $penduduk) {
        $dokumen = collect();
        if (class_exists(\App\Models\DokumenPenduduk::class)) {
            $dokumen = \App\Models\DokumenPenduduk::where('penduduk_id', $penduduk->id)
                ->latest()->paginate(10);
        }
        return view('admin.penduduk-dokumen', compact('penduduk', 'dokumen'));
    }

    public function dokumenStore(Request $request, Penduduk $penduduk) {
        $request->validate([
            'nama_dokumen'  => 'required|string|max:100',
            'jenis_dokumen' => 'nullable|string|max:100',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if (!class_exists(\App\Models\DokumenPenduduk::class)) {
            return back()->with('error', 'Fitur dokumen penduduk belum tersedia. Buat model DokumenPenduduk terlebih dahulu.');
        }

        $path = $request->file('file')->store("dokumen_penduduk/{$penduduk->id}", 'public');

        \App\Models\DokumenPenduduk::create([
            'penduduk_id'   => $penduduk->id,
            'nama_dokumen'  => $request->nama_dokumen,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path'     => $path,
            'mime_type'     => $request->file('file')->getMimeType(),
            'ukuran'        => $request->file('file')->getSize(),
            'uploaded_by'   => auth()->id(),
        ]);

        return back()->with('success', 'Dokumen berhasil diupload.');
    }

    public function dokumenDestroy(Penduduk $penduduk, $dokumenId) {
        if (!class_exists(\App\Models\DokumenPenduduk::class)) {
            return back()->with('error', 'Fitur dokumen penduduk belum tersedia.');
        }
        $doc = \App\Models\DokumenPenduduk::where('penduduk_id', $penduduk->id)->findOrFail($dokumenId);
        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();
        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    // =========================================================================
    // HELPER PRIVATE
    // =========================================================================
    private function buildExportQuery(Request $request) {
        return Penduduk::query()
            ->when(
                $request->filled('search'),
                fn($q) => $q->where(fn($q2) => $q2
                    ->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('nik', 'like', "%{$request->search}%"))
            )
            ->when(
                $request->filled('status_dasar') && $request->status_dasar !== 'semua',
                fn($q) => $q->where('status_dasar', $request->status_dasar)
            )
            ->when(
                $request->filled('jenis_tambah') && $request->jenis_tambah !== 'semua',
                fn($q) => $q->where('jenis_tambah', $request->jenis_tambah)
            )
            ->when(
                $request->filled('jenis_kelamin') && $request->jenis_kelamin !== 'semua',
                fn($q) => $q->where('jenis_kelamin', $request->jenis_kelamin)
            )
            ->when(
                $request->filled('dusun'),
                fn($q) => $q->whereHas('wilayah', fn($wq) => $wq->where('dusun', $request->dusun))
            )
            ->orderBy('nama');
    }
}
