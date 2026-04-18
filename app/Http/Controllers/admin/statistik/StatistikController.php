<?php

namespace App\Http\Controllers\Admin\statistik;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller {
    public function index() {
        $penduduks = Penduduk::where('status_hidup', 'hidup')
            ->select('tanggal_lahir', 'jenis_kelamin')->get();

        $total_penduduk = Penduduk::where('status_hidup', 'hidup')->count();
        $laki_laki = Penduduk::where('status_hidup', 'hidup')->where('jenis_kelamin', 'L')->count();
        $perempuan = Penduduk::where('status_hidup', 'hidup')->where('jenis_kelamin', 'P')->count();

        $usia = ['0_5' => 0, '6_17' => 0, '18_59' => 0, '60_plus' => 0];
        foreach ($penduduks as $p) {
            if (!$p->tanggal_lahir) continue;
            try {
                $age = Carbon::parse($p->tanggal_lahir)->age;
            } catch (\Exception $e) {
                continue;
            }
            if ($age <= 5) $usia['0_5']++;
            elseif ($age >= 6 && $age <= 17) $usia['6_17']++;
            elseif ($age >= 18 && $age <= 59) $usia['18_59']++;
            else $usia['60_plus']++;
        }

        $pendidikan_data = DB::table('penduduk')
            ->join('ref_pendidikan', 'penduduk.pendidikan_kk_id', '=', 'ref_pendidikan.id')
            ->where('penduduk.status_hidup', 'hidup')->whereNull('penduduk.deleted_at')
            ->groupBy('ref_pendidikan.nama')
            ->selectRaw('ref_pendidikan.nama as label, COUNT(*) as jumlah')
            ->orderByRaw('jumlah DESC')->get()->toArray();
        $pendidikan = [];
        foreach ($pendidikan_data as $item) {
            if (!empty($item->label)) $pendidikan[] = ['label' => ucfirst($item->label), 'jumlah' => (int)$item->jumlah];
        }

        $pekerjaan_data = DB::table('penduduk')
            ->join('ref_pekerjaan', 'penduduk.pekerjaan_id', '=', 'ref_pekerjaan.id')
            ->where('penduduk.status_hidup', 'hidup')->whereNull('penduduk.deleted_at')
            ->groupBy('ref_pekerjaan.nama')
            ->selectRaw('ref_pekerjaan.nama as label, COUNT(*) as jumlah')
            ->orderByRaw('jumlah DESC')->get()->toArray();
        $pekerjaan = [];
        foreach ($pekerjaan_data as $item) {
            if (!empty($item->label)) $pekerjaan[] = ['label' => ucfirst($item->label), 'jumlah' => (int)$item->jumlah];
        }

        $status_nikah_data = DB::table('penduduk')
            ->join('ref_status_kawin', 'penduduk.status_kawin_id', '=', 'ref_status_kawin.id')
            ->where('penduduk.status_hidup', 'hidup')->whereNull('penduduk.deleted_at')
            ->groupBy('ref_status_kawin.nama')
            ->selectRaw('ref_status_kawin.nama as label, COUNT(*) as jumlah')
            ->orderByRaw('jumlah DESC')->get()->toArray();
        $status_perkawinan = [];
        foreach ($status_nikah_data as $item) {
            if (!empty($item->label)) $status_perkawinan[] = ['label' => ucfirst($item->label), 'jumlah' => (int)$item->jumlah];
        }

        $data = [
            'total_penduduk'    => (int)$total_penduduk,
            'laki_laki'         => (int)$laki_laki,
            'perempuan'         => (int)$perempuan,
            'kepala_keluarga'   => Keluarga::count(),
            'rt'                => Wilayah::whereNotNull('rt')->where('rt', '!=', '')->distinct('rt')->count('rt'),
            'rw'                => Wilayah::whereNotNull('rw')->where('rw', '!=', '')->distinct('rw')->count('rw'),
            'usia'              => $usia,
            'pendidikan'        => $pendidikan,
            'pekerjaan'         => $pekerjaan,
            'status_perkawinan' => $status_perkawinan,
        ];

        return view('admin.statistik.statistik', compact('data'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    public function penduduk() {
        $penduduk = Penduduk::with(['keluarga', 'agama', 'pendidikanKk', 'pekerjaan', 'statusKawin'])
            ->where('status_hidup', 'hidup')
            ->when(request('search'), fn($q, $s) => $q->where('nama', 'like', "%$s%")->orWhere('nik', 'like', "%$s%"))
            ->when(request('jenis_kelamin'), fn($q, $jk) => $q->where('jenis_kelamin', $jk))
            ->when(request('agama'), fn($q, $a) => $q->whereHas('agama', fn($r) => $r->where('nama', $a)))
            ->orderBy('nama')
            ->paginate(50);

        $total_penduduk  = Penduduk::where('status_hidup', 'hidup')->count();
        $laki_laki       = Penduduk::where('status_hidup', 'hidup')->where('jenis_kelamin', 'L')->count();
        $perempuan       = Penduduk::where('status_hidup', 'hidup')->where('jenis_kelamin', 'P')->count();
        $kepala_keluarga = Keluarga::count();

        $data = compact('penduduk', 'total_penduduk', 'laki_laki', 'perempuan', 'kepala_keluarga');
        return view('admin.statistik.penduduk', compact('data'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    public function kependudukan(Request $request) {
        $dusunFilter = $request->get('dusun');
        $kategori    = $request->get('kategori', 'pendidikan');

        // Base query helper
        $base = fn() => DB::table('penduduk')
            ->where('penduduk.status_hidup', 'hidup')
            ->whereNull('penduduk.deleted_at')
            ->when($dusunFilter, function ($q) use ($dusunFilter) {
                $q->join('wilayah as w_filter', 'penduduk.wilayah_id', '=', 'w_filter.id')
                    ->where('w_filter.dusun', $dusunFilter);
            });

        $total_penduduk  = $base()->count();
        $laki_laki       = $base()->where('penduduk.jenis_kelamin', 'L')->count();
        $perempuan       = $base()->where('penduduk.jenis_kelamin', 'P')->count();
        $kepala_keluarga = Keluarga::count();
        $rt = Wilayah::whereNotNull('rt')->where('rt', '!=', '')->distinct('rt')->count('rt');
        $rw = Wilayah::whereNotNull('rw')->where('rw', '!=', '')->distinct('rw')->count('rw');

        // Helper buat tabel breakdown L/P
        $makeTable = function (array $rows, int $total): array {
            $result = [];
            foreach ($rows as $item) {
                $label = !empty($item->label) ? ucfirst($item->label) : '(Tidak Diisi)';
                $tot = (int)$item->total;
                $l   = (int)$item->laki;
                $p   = (int)$item->perempuan;
                $result[] = [
                    'label'            => $label,
                    'total'            => $tot,
                    'laki'             => $l,
                    'perempuan'        => $p,
                    'persen'           => $total > 0 ? round($tot / $total * 100, 2) : 0,
                    'persen_laki'      => $total > 0 ? round($l / $total * 100, 2)   : 0,
                    'persen_perempuan' => $total > 0 ? round($p / $total * 100, 2)   : 0,
                ];
            }
            return $result;
        };

        // ── USIA ──────────────────────────────────────────────────────────
        $rentangUsia = [
            ['label' => 'Balita (0–4 th)',  'min' => 0,  'max' => 4],
            ['label' => 'Anak (5–12 th)',   'min' => 5,  'max' => 12],
            ['label' => 'Remaja (13–17 th)', 'min' => 13, 'max' => 17],
            ['label' => 'Dewasa (18–59 th)', 'min' => 18, 'max' => 59],
            ['label' => 'Lansia (60+ th)',  'min' => 60, 'max' => 999],
        ];
        $usia_rows = [];
        foreach ($rentangUsia as $r) {
            $q = $base();
            if ($r['max'] < 999) $q->whereRaw("TIMESTAMPDIFF(YEAR,penduduk.tanggal_lahir,CURDATE()) BETWEEN {$r['min']} AND {$r['max']}");
            else               $q->whereRaw("TIMESTAMPDIFF(YEAR,penduduk.tanggal_lahir,CURDATE()) >= {$r['min']}");
            $tot = $q->count();
            $l   = (clone $q)->where('penduduk.jenis_kelamin', 'L')->count();
            $p   = (clone $q)->where('penduduk.jenis_kelamin', 'P')->count();
            $usia_rows[] = (object)['label' => $r['label'], 'total' => $tot, 'laki' => $l, 'perempuan' => $p];
        }
        $usia = $makeTable($usia_rows, $total_penduduk);

        // ── PENDIDIKAN ────────────────────────────────────────────────────
        $pendidikan = $makeTable(
            $base()->leftJoin('ref_pendidikan', 'penduduk.pendidikan_kk_id', '=', 'ref_pendidikan.id')
                ->groupBy('ref_pendidikan.nama')
                ->selectRaw('ref_pendidikan.nama as label,
                    COUNT(*) as total,
                    SUM(CASE WHEN penduduk.jenis_kelamin="L" THEN 1 ELSE 0 END) as laki,
                    SUM(CASE WHEN penduduk.jenis_kelamin="P" THEN 1 ELSE 0 END) as perempuan')
                ->orderByRaw('total DESC')->get()->toArray(),
            $total_penduduk
        );

        // ── PEKERJAAN ─────────────────────────────────────────────────────
        $pekerjaan = $makeTable(
            $base()->leftJoin('ref_pekerjaan', 'penduduk.pekerjaan_id', '=', 'ref_pekerjaan.id')
                ->groupBy('ref_pekerjaan.nama')
                ->selectRaw('ref_pekerjaan.nama as label,
                    COUNT(*) as total,
                    SUM(CASE WHEN penduduk.jenis_kelamin="L" THEN 1 ELSE 0 END) as laki,
                    SUM(CASE WHEN penduduk.jenis_kelamin="P" THEN 1 ELSE 0 END) as perempuan')
                ->orderByRaw('total DESC')->get()->toArray(),
            $total_penduduk
        );

        // ── AGAMA ─────────────────────────────────────────────────────────
        $agama = $makeTable(
            $base()->leftJoin('ref_agama', 'penduduk.agama_id', '=', 'ref_agama.id')
                ->groupBy('ref_agama.nama')
                ->selectRaw('ref_agama.nama as label,
                    COUNT(*) as total,
                    SUM(CASE WHEN penduduk.jenis_kelamin="L" THEN 1 ELSE 0 END) as laki,
                    SUM(CASE WHEN penduduk.jenis_kelamin="P" THEN 1 ELSE 0 END) as perempuan')
                ->orderByRaw('total DESC')->get()->toArray(),
            $total_penduduk
        );

        // ── GOLONGAN DARAH ────────────────────────────────────────────────
        $golongan_darah = $makeTable(
            $base()->leftJoin('ref_golongan_darah', 'penduduk.golongan_darah_id', '=', 'ref_golongan_darah.id')
                ->groupBy('ref_golongan_darah.nama')
                ->selectRaw('ref_golongan_darah.nama as label,
                    COUNT(*) as total,
                    SUM(CASE WHEN penduduk.jenis_kelamin="L" THEN 1 ELSE 0 END) as laki,
                    SUM(CASE WHEN penduduk.jenis_kelamin="P" THEN 1 ELSE 0 END) as perempuan')
                ->orderByRaw('total DESC')->get()->toArray(),
            $total_penduduk
        );

        // ── STATUS KAWIN ──────────────────────────────────────────────────
        $status_kawin = $makeTable(
            $base()->leftJoin('ref_status_kawin', 'penduduk.status_kawin_id', '=', 'ref_status_kawin.id')
                ->groupBy('ref_status_kawin.nama')
                ->selectRaw('ref_status_kawin.nama as label,
                    COUNT(*) as total,
                    SUM(CASE WHEN penduduk.jenis_kelamin="L" THEN 1 ELSE 0 END) as laki,
                    SUM(CASE WHEN penduduk.jenis_kelamin="P" THEN 1 ELSE 0 END) as perempuan')
                ->orderByRaw('total DESC')->get()->toArray(),
            $total_penduduk
        );

        // ── JENIS KELAMIN ─────────────────────────────────────────────────
        $jenis_kelamin = [
            [
                'label' => 'Laki-laki',
                'total' => $laki_laki,
                'laki' => $laki_laki,
                'perempuan' => 0,
                'persen' => $total_penduduk > 0 ? round($laki_laki / $total_penduduk * 100, 2) : 0,
                'persen_laki' => $total_penduduk > 0 ? round($laki_laki / $total_penduduk * 100, 2) : 0,
                'persen_perempuan' => 0
            ],
            [
                'label' => 'Perempuan',
                'total' => $perempuan,
                'laki' => 0,
                'perempuan' => $perempuan,
                'persen' => $total_penduduk > 0 ? round($perempuan / $total_penduduk * 100, 2) : 0,
                'persen_laki' => 0,
                'persen_perempuan' => $total_penduduk > 0 ? round($perempuan / $total_penduduk * 100, 2) : 0
            ],
        ];

        // ── WILAYAH ───────────────────────────────────────────────────────
        $wilayah = $makeTable(
            DB::table('penduduk')
                ->join('wilayah', 'penduduk.wilayah_id', '=', 'wilayah.id')
                ->where('penduduk.status_hidup', 'hidup')->whereNull('penduduk.deleted_at')
                ->whereNotNull('wilayah.dusun')
                ->when($dusunFilter, fn($q) => $q->where('wilayah.dusun', $dusunFilter))
                ->groupBy('wilayah.dusun')
                ->selectRaw('wilayah.dusun as label,
                    COUNT(*) as total,
                    SUM(CASE WHEN penduduk.jenis_kelamin="L" THEN 1 ELSE 0 END) as laki,
                    SUM(CASE WHEN penduduk.jenis_kelamin="P" THEN 1 ELSE 0 END) as perempuan')
                ->orderByRaw('total DESC')->get()->toArray(),
            $total_penduduk
        );

        // ── Daftar dusun ──────────────────────────────────────────────────
        $dusunList = Wilayah::whereNotNull('dusun')->where('dusun', '!=', '')
            ->distinct()->orderBy('dusun')->pluck('dusun');

        // ── Daftar perangkat untuk modal cetak/unduh ──────────────────────
        $perangkatList = DB::table('perangkat_desa')
            ->leftJoin('jabatan_perangkat', 'perangkat_desa.jabatan_id', '=', 'jabatan_perangkat.id')
            ->select('perangkat_desa.nama', 'jabatan_perangkat.nama as jabatan')
            ->whereNull('perangkat_desa.deleted_at')
            ->orderBy('perangkat_desa.urutan')
            ->get();

        // Jika tabel perangkat_desa kosong, gunakan identitas_desa sebagai fallback
        if ($perangkatList->isEmpty()) {
            $identitas = DB::table('identitas_desa')->first();
            $namaKades = $identitas->kepala_desa ?? ($identitas->nama_kepala_desa ?? 'Kepala Desa');
            $perangkatList = collect([(object)['nama' => $namaKades, 'jabatan' => 'Kepala Desa']]);
        }

        $data = [
            'total_penduduk'  => $total_penduduk,
            'laki_laki'       => $laki_laki,
            'perempuan'       => $perempuan,
            'kepala_keluarga' => $kepala_keluarga,
            'rt'              => $rt,
            'rw'              => $rw,
            'usia'            => $usia,
            'pendidikan'      => $pendidikan,
            'pekerjaan'       => $pekerjaan,
            'agama'           => $agama,
            'golongan_darah'  => $golongan_darah,
            'status_kawin'    => $status_kawin,
            'jenis_kelamin'   => $jenis_kelamin,
            'wilayah'         => $wilayah,
            'dusunList'       => $dusunList,
            'dusunFilter'     => $dusunFilter,
            'kategori'        => $kategori,
            'perangkatList'   => $perangkatList,
        ];

        return view('admin.statistik.kependudukan', compact('data'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    public function laporanBulanan(Request $request) {
        $month = $request->query('month');
        $year  = $request->query('year');

        $now = Carbon::now();
        if ($month && $year) {
            try {
                $start = Carbon::createFromDate((int)$year, (int)$month, 1)->startOfDay();
            } catch (\Exception $e) {
                $start = $now->copy()->startOfMonth();
            }
        } else {
            $start = $now->copy()->startOfMonth();
        }
        $end   = $start->copy()->endOfMonth()->endOfDay();
        $year  = $start->year;
        $month = $start->month;

        $total_penduduk = Penduduk::where('status_hidup', 'hidup')->count();
        $lahir   = Penduduk::whereYear('tanggal_lahir', $year)->whereMonth('tanggal_lahir', $month)->whereBetween('created_at', [$start, $end])->count();
        $created = Penduduk::whereBetween('created_at', [$start, $end])->count();
        $datang  = max(0, $created - $lahir);
        $meninggal = Penduduk::where('status_hidup', 'meninggal')->whereBetween('updated_at', [$start, $end])->count();
        $pindah  = 0;
        $mutasi  = compact('lahir', 'meninggal', 'datang', 'pindah');

        $makePercent = fn($count) => ($total_penduduk > 0 ? '+' . round($count / $total_penduduk * 100, 2) : '+0') . '%';
        $laporan = [
            ['kategori' => 'Kelahiran', 'jumlah' => $lahir,    'persen' => $makePercent($lahir)],
            ['kategori' => 'Kematian', 'jumlah' => $meninggal, 'persen' => $makePercent($meninggal)],
            ['kategori' => 'Pendatang', 'jumlah' => $datang,   'persen' => $makePercent($datang)],
            ['kategori' => 'Pindah',   'jumlah' => $pindah,   'persen' => $makePercent($pindah)],
        ];

        $data = ['bulan' => $start->translatedFormat('F Y'), 'total_penduduk' => $total_penduduk, 'mutasi' => $mutasi, 'laporan' => $laporan];
        return view('admin.statistik.laporan-bulanan', compact('data'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    public function kelompokRentan(Request $request) {
        $bulan = (int) now()->month;
        $tahun = (int) now()->year;
        $dusunFilter = $request->get('dusun');

        $identitas = DB::table('identitas_desa')->first();

        $dusunList = Wilayah::whereNotNull('dusun')
            ->where('dusun', '!=', '')
            ->whereNull('deleted_at')
            ->distinct()
            ->orderBy('dusun')
            ->pluck('dusun');

        $bulanList = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // ── Query utama: data per kombinasi dusun → rw → rt ──────────────────
        // Catatan: sakit_menahun_id = 1 diasumsikan "TIDAK" (sama pola ref_cacat id=1=TIDAK ADA)
        //          Ubah kondisi sakit_menahun_id jika ref_sakit_menahun berbeda.
        $rows = DB::table('penduduk as p')
            ->join('wilayah as w', 'p.wilayah_id', '=', 'w.id')
            ->where('p.status_hidup', 'hidup')
            ->whereNull('p.deleted_at')
            ->whereNull('w.deleted_at')
            ->whereNotNull('w.dusun')
            ->when($dusunFilter, fn($q) => $q->where('w.dusun', $dusunFilter))
            ->groupBy('w.dusun', 'w.rw', 'w.rt')
            ->selectRaw("
            w.dusun,
            w.rw,
            w.rt,
            -- Kelompok Umur (ref: Lampiran A-9 OpenSID)
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE()) < 1              THEN 1 ELSE 0 END) AS umur_bawah_1,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE()) BETWEEN 1 AND 5  THEN 1 ELSE 0 END) AS umur_1_5,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE()) BETWEEN 6 AND 12 THEN 1 ELSE 0 END) AS umur_6_12,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE()) BETWEEN 13 AND 15 THEN 1 ELSE 0 END) AS umur_13_15,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE()) BETWEEN 16 AND 18 THEN 1 ELSE 0 END) AS umur_16_18,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, p.tanggal_lahir, CURDATE()) >= 60             THEN 1 ELSE 0 END) AS umur_atas_60,
            -- Disabilitas (ref_cacat: 2=Fisik,3=Netra/Buta,4=Rungu/Wicara,5=Mental/Jiwa,6=Fisik&Mental,7=Lainnya,1=Tidak Ada)
            SUM(CASE WHEN p.cacat_id = 2 THEN 1 ELSE 0 END) AS disab_fisik,
            SUM(CASE WHEN p.cacat_id = 3 THEN 1 ELSE 0 END) AS disab_netra,
            SUM(CASE WHEN p.cacat_id = 4 THEN 1 ELSE 0 END) AS disab_rungu,
            SUM(CASE WHEN p.cacat_id = 5 THEN 1 ELSE 0 END) AS disab_mental,
            SUM(CASE WHEN p.cacat_id = 6 THEN 1 ELSE 0 END) AS disab_fisik_mental,
            SUM(CASE WHEN p.cacat_id = 7 THEN 1 ELSE 0 END) AS disab_lainnya,
            SUM(CASE WHEN p.cacat_id = 1 OR p.cacat_id IS NULL THEN 1 ELSE 0 END) AS tidak_disabilitas,
            -- Sakit Menahun terpisah L/P
            SUM(CASE WHEN p.sakit_menahun_id IS NOT NULL AND p.sakit_menahun_id != 1 AND p.jenis_kelamin = 'L' THEN 1 ELSE 0 END) AS sakit_l,
            SUM(CASE WHEN p.sakit_menahun_id IS NOT NULL AND p.sakit_menahun_id != 1 AND p.jenis_kelamin = 'P' THEN 1 ELSE 0 END) AS sakit_p,
            -- Hamil
            SUM(CASE WHEN p.hamil = 1 THEN 1 ELSE 0 END) AS hamil
        ")
            ->orderBy('w.dusun')
            ->orderBy('w.rw')
            ->orderBy('w.rt')
            ->get();

        // ── Query KK per wilayah (kepala keluarga dari tabel keluarga) ────────
        $kkMap = DB::table('keluarga as k')
            ->join('wilayah as w', 'k.wilayah_id', '=', 'w.id')
            ->join('penduduk as p', 'k.kepala_keluarga_id', '=', 'p.id')
            ->whereNull('k.deleted_at')
            ->whereNull('w.deleted_at')
            ->whereNotNull('w.dusun')
            ->when($dusunFilter, fn($q) => $q->where('w.dusun', $dusunFilter))
            ->groupBy('w.dusun', 'w.rw', 'w.rt')
            ->selectRaw("
            w.dusun, w.rw, w.rt,
            SUM(CASE WHEN p.jenis_kelamin = 'L' THEN 1 ELSE 0 END) AS kk_l,
            SUM(CASE WHEN p.jenis_kelamin = 'P' THEN 1 ELSE 0 END) AS kk_p
        ")
            ->get()
            ->keyBy(fn($r) => "{$r->dusun}|{$r->rw}|{$r->rt}");

        // ── Gabungkan KK ke dalam rows utama ─────────────────────────────────
        $tableRows = $rows->map(function ($row) use ($kkMap) {
            $key       = "{$row->dusun}|{$row->rw}|{$row->rt}";
            $kk        = $kkMap->get($key);
            $row->kk_l = $kk->kk_l ?? 0;
            $row->kk_p = $kk->kk_p ?? 0;
            return $row;
        });

        $data = [
            'tableRows'   => $tableRows,
            'dusunList'   => $dusunList,
            'dusunFilter' => $dusunFilter,
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'bulanList'   => $bulanList,
            'identitas'   => $identitas,
        ];

        // ── Export Excel ──────────────────────────────────────────────────────
        if ($request->get('export') === 'excel') {
            return $this->exportExcelKelompokRentan($data);
        }

        return view('admin.statistik.kelompok-rentan', compact('data'));
    }

    // ─────────────────────────────────────────────────────────────────────────────
    private function exportExcelKelompokRentan(array $data): \Illuminate\Http\Response {
        $rows      = $data['tableRows'];
        $bulanList = $data['bulanList'];
        $bulan     = $data['bulan'];
        $tahun     = $data['tahun'];
        $identitas = $data['identitas'];

        $namaDesa  = $identitas->nama_desa  ?? ($identitas->nama             ?? 'Desa');
        $kecamatan = $identitas->kecamatan  ?? ($identitas->nama_kecamatan   ?? '-');
        $kabupaten = $identitas->kabupaten  ?? ($identitas->nama_kabupaten   ?? '-');

        $cols = [
            'kk_l',
            'kk_p',
            'umur_bawah_1',
            'umur_1_5',
            'umur_6_12',
            'umur_13_15',
            'umur_16_18',
            'umur_atas_60',
            'disab_fisik',
            'disab_netra',
            'disab_rungu',
            'disab_mental',
            'disab_fisik_mental',
            'disab_lainnya',
            'tidak_disabilitas',
            'sakit_l',
            'sakit_p',
            'hamil',
        ];
        $totals = array_fill_keys($cols, 0);
        foreach ($rows as $row) {
            foreach ($cols as $c) {
                $totals[$c] += (int)($row->$c ?? 0);
            }
        }

        $namaBulan = $bulanList[$bulan] ?? $bulan;
        $kabUp     = mb_strtoupper($kabupaten);

        // Helper: render a number cell that Excel treats as a real number
        $numCell = fn(int $n) => '<td style="mso-number-format:\'0\'; text-align:center;">' . $n . '</td>';

        ob_start();
?>
        <!DOCTYPE html>
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:x="urn:schemas-microsoft-com:office:excel"
            xmlns="http://www.w3.org/TR/REC-html40">

        <head>
            <meta charset="UTF-8">
            <!--[if gte mso 9]>
<xml>
  <x:ExcelWorkbook>
    <x:ExcelWorksheets>
      <x:ExcelWorksheet>
        <x:Name>Kelompok Rentan</x:Name>
        <x:WorksheetOptions>
          <x:PageSetup>
            <x:Layout x:Orientation="Landscape"/>
            <x:FitToPage/>
          </x:PageSetup>
          <x:Print>
            <x:FitWidth>1</x:FitWidth>
            <x:FitHeight>1</x:FitHeight>
          </x:Print>
        </x:WorksheetOptions>
      </x:ExcelWorksheet>
    </x:ExcelWorksheets>
  </x:ExcelWorkbook>
</xml>
<![endif]-->
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 9pt;
                }

                table {
                    border-collapse: collapse;
                }

                td,
                th {
                    border: 1px solid #000;
                    padding: 4px 6px;
                    vertical-align: middle;
                    text-align: center;
                    white-space: nowrap;
                    font-size: 9pt;
                }

                .hl {
                    text-align: left;
                }

                .bold {
                    font-weight: bold;
                }

                .head-loc {
                    background: #1F3864;
                    color: #FFFFFF;
                    font-weight: bold;
                    font-size: 8pt;
                }

                .head-kk {
                    background: #1F497D;
                    color: #FFFFFF;
                    font-weight: bold;
                    font-size: 8pt;
                }

                .head-umur {
                    background: #2E75B6;
                    color: #FFFFFF;
                    font-weight: bold;
                    font-size: 8pt;
                }

                .head-disab {
                    background: #843C0C;
                    color: #FFFFFF;
                    font-weight: bold;
                    font-size: 8pt;
                }

                .head-sakit {
                    background: #375623;
                    color: #FFFFFF;
                    font-weight: bold;
                    font-size: 8pt;
                }

                .head-hamil {
                    background: #4D3563;
                    color: #FFFFFF;
                    font-weight: bold;
                    font-size: 8pt;
                }

                .info-label {
                    text-align: left;
                    min-width: 100px;
                    border: none;
                }

                .info-colon {
                    text-align: center;
                    border: none;
                    width: 16px;
                }

                .info-val {
                    text-align: left;
                    font-weight: bold;
                    border: none;
                }

                .title-row td {
                    border: none;
                }

                .total-row td {
                    background: #E8F5E9;
                    font-weight: bold;
                    border-top: 2px solid #388E3C;
                }

                tr.odd td {
                    background: #FFFFFF;
                }

                tr.even td {
                    background: #F7FBFF;
                }
            </style>
        </head>

        <body>
            <table>
                <!-- ── JUDUL ── -->
                <tr class="title-row">
                    <td colspan="21" class="bold" style="text-align:center; font-size:11pt; border:none; padding-bottom:2px;">
                        PEMERINTAH KABUPATEN/KOTA <?= htmlspecialchars($kabUp) ?>
                    </td>
                </tr>
                <tr class="title-row">
                    <td colspan="21" class="bold" style="text-align:center; font-size:9pt; border:none; padding-bottom:2px;">
                        DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN A - 9)
                    </td>
                </tr>
                <tr class="title-row">
                    <td colspan="21" style="border:none; height:6px;"></td>
                </tr>

                <!-- ── INFO DESA ── -->
                <tr>
                    <td colspan="5" class="info-label">Desa/Kelurahan</td>
                    <td colspan="1" class="info-colon">:</td>
                    <td colspan="15" class="info-val"><?= htmlspecialchars($namaDesa) ?></td>
                </tr>
                <tr>
                    <td colspan="5" class="info-label">Kecamatan</td>
                    <td colspan="1" class="info-colon">:</td>
                    <td colspan="15" class="info-val"><?= htmlspecialchars($kecamatan) ?></td>
                </tr>
                <tr>
                    <td colspan="5" class="info-label">Laporan Bulan</td>
                    <td colspan="1" class="info-colon">:</td>
                    <td colspan="15" class="info-val"><?= htmlspecialchars($namaBulan . ' ' . $tahun) ?></td>
                </tr>
                <?php if (!empty($data['dusunFilter'])): ?>
                    <tr>
                        <td colspan="5" class="info-label">Dusun</td>
                        <td colspan="1" class="info-colon">:</td>
                        <td colspan="15" class="info-val"><?= htmlspecialchars($data['dusunFilter']) ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td colspan="21" style="border:none; height:8px;"></td>
                </tr>

                <!-- ── HEADER TABEL ── -->
                <tr>
                    <th rowspan="2" class="head-loc" style="min-width:90px">DUSUN</th>
                    <th rowspan="2" class="head-loc">RW</th>
                    <th rowspan="2" class="head-loc">RT</th>
                    <th colspan="2" class="head-kk">KK</th>
                    <th colspan="6" class="head-umur">KONDISI DAN KELOMPOK UMUR</th>
                    <th colspan="7" class="head-disab">DISABILITAS</th>
                    <th colspan="2" class="head-sakit">SAKIT MENAHUN</th>
                    <th rowspan="2" class="head-hamil">HAMIL</th>
                </tr>
                <tr>
                    <th class="head-kk">L</th>
                    <th class="head-kk">P</th>
                    <th class="head-umur">DI BAWAH&#10;1 TAHUN</th>
                    <th class="head-umur">1-5 TAHUN</th>
                    <th class="head-umur">6-12 TAHUN</th>
                    <th class="head-umur">13-15 TAHUN</th>
                    <th class="head-umur">16-18 TAHUN</th>
                    <th class="head-umur">DI ATAS&#10;60 TAHUN</th>
                    <th class="head-disab">DISABILITAS FISIK</th>
                    <th class="head-disab">DISABILITAS NETRA/BUTA</th>
                    <th class="head-disab">DISABILITAS RUNGU/WICARA</th>
                    <th class="head-disab">DISABILITAS MENTAL/JIWA</th>
                    <th class="head-disab">DISABILITAS FISIK &amp; MENTAL</th>
                    <th class="head-disab">DISABILITAS LAINNYA</th>
                    <th class="head-disab">TIDAK DISABILITAS</th>
                    <th class="head-sakit">L</th>
                    <th class="head-sakit">P</th>
                </tr>

                <!-- ── DATA ROWS ── -->
                <?php $i = 0;
                foreach ($rows as $row): $i++;
                    $cls = ($i % 2 === 0) ? 'even' : 'odd'; ?>
                    <tr class="<?= $cls ?>">
                        <td class="hl bold" style="background: <?= ($i % 2 === 0) ? '#EFF6FF' : '#F8FAFF' ?>;"><?= htmlspecialchars($row->dusun ?? '') ?></td>
                        <td><?= htmlspecialchars($row->rw ?? '') ?></td>
                        <td><?= htmlspecialchars($row->rt ?? '') ?></td>
                        <?php foreach ($cols as $c): echo $numCell((int)($row->$c ?? 0));
                        endforeach; ?>
                    </tr>
                <?php endforeach; ?>

                <!-- ── TOTAL ROW ── -->
                <tr class="total-row">
                    <td colspan="3" class="hl bold">Total</td>
                    <?php foreach ($cols as $c): echo $numCell($totals[$c]);
                    endforeach; ?>
                </tr>

                <!-- ── TANGGAL ── -->
                <tr>
                    <td colspan="21" style="border:none; height:10px;"></td>
                </tr>
                <tr>
                    <td colspan="21" style="text-align:left; border:none; font-size:8pt;">
                        Tanggal cetak : <?= date('d/m/Y') ?>
                    </td>
                </tr>
            </table>
        </body>

        </html>
<?php
        $html     = ob_get_clean();
        $filename = 'kelompok_rentan_' . str_replace(' ', '_', $namaBulan) . '_' . $tahun . '.xls';

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }
}
