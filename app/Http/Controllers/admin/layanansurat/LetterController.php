<?php

namespace App\Http\Controllers\Admin\LayananSurat;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\Penduduk;
use App\Models\IdentitasDesa;
use App\Models\ArsipSurat;
use App\Models\SuratTemplate;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class LetterController extends Controller {
    public function index() {
        $templates = SuratTemplate::where('status', 'aktif')->get();
        return view('admin.layanan-surat.letters.index', compact('templates'));
    }

    public function create(Request $request) {
        $templateId = $request->query('id');
        $selectedTemplate = SuratTemplate::findOrFail($templateId);

        $variables = [];
        if ($selectedTemplate->konten_template) {
            preg_match_all('/\[([a-zA-Z0-9_]+)\]/i', $selectedTemplate->konten_template, $matches);
            $variables = array_unique($matches[1] ?? []);
        }

        $autoNomorSurat = $this->generateAutoNomorSurat($templateId);

        return view('admin.layanan-surat.letters.create', compact('selectedTemplate', 'variables', 'autoNomorSurat'));
    }

    /**
     * PERBAIKAN DI SINI: Menambahkan data IdentitasDesa otomatis
     */
    public function preview(Request $request) {
        if (!$request->filled('format_nomor') && $request->filled('template_id')) {
            $autoNomor = $this->generateAutoNomorSurat($request->template_id);
            $request->merge([
                'format_nomor' => $autoNomor,
                'nomor_surat'  => $autoNomor
            ]);
        }

        $request->validate([
            'format_nomor' => 'required|unique:arsip_surat,nomor_surat',
            'template_id'  => 'required|exists:surat_templates,id'
        ], [
            'format_nomor.required' => 'Nomor surat wajib diisi.',
            'format_nomor.unique'   => 'Nomor surat sudah terdaftar di arsip!',
        ]);

        $template = SuratTemplate::findOrFail($request->template_id);
        $htmlContent = $template->konten_template;

        // 1. Ambil Data Desa dari Database
        $desa = IdentitasDesa::first();

        // 2. Siapkan data dari Form
        $formData = $request->except(['_token', 'template_id']);

        // 3. Gabungkan Data Form + Data Desa Otomatis
        $dataReplace = array_merge($formData, [
            'nama_desa'      => $desa->nama_desa ?? '',
            'kecamatan'      => $desa->nama_kecamatan ?? '',
            'nama_kabupaten' => $desa->nama_kabupaten ?? '',
            'sebutan_desa'   => $desa->sebutan_desa ?? 'Desa',
            'kode_provinsi'  => $desa->kode_provinsi ?? '',
            'logo_desa'      => $desa->logo ? asset('storage/' . $desa->logo) : '',
            'alamat_kantor'  => $desa->alamat_kantor ?? '',
            'kepala_desa'    => $desa->nama_kepala_desa ?? '',
            'nip_kepala_desa' => $desa->nip_kepala_desa ?? '-',
            'tgl_surat'      => now()->translatedFormat('d F Y'),
        ]);

        // 4. Proses Penggantian variabel [tag]
        foreach ($dataReplace as $key => $value) {
            $htmlContent = str_ireplace('[' . $key . ']', $value ?? '', $htmlContent);
        }

        return view('admin.layanan-surat.letters.preview', [
            'htmlContent' => $htmlContent,
            'formData'    => $formData,
            'template'    => $template
        ]);
    }

    public function generateFinal(Request $request) {
        try {
            $content = $request->final_content;

            $pdf = Pdf::loadHTML($content)->setPaper('a4', 'portrait');

            $fileName = 'Surat-' . Str::slug($request->nama_pemohon ?? 'dokumen') . '-' . time() . '.pdf';
            $dbPath = 'arsip_surat/' . $fileName;
            $fullPath = storage_path('app/public/' . $dbPath);

            if (!File::exists(storage_path('app/public/arsip_surat'))) {
                File::makeDirectory(storage_path('app/public/arsip_surat'), 0755, true);
            }
            File::put($fullPath, $pdf->output());

            ArsipSurat::create([
                'nomor_surat'   => $request->nomor_surat,
                'jenis_surat'   => $request->jenis_surat,
                'nama_pemohon'  => $request->nama_pemohon,
                'nik'           => $request->nik_pemohon ?? '-',
                'tanggal_surat' => $request->tanggal_surat ?? now()->format('Y-m-d'),
                'file_path'     => $dbPath,
                'status'        => 'selesai',
                'user_id'       => Auth::id() ?? 1,
            ]);

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return redirect()->route('admin.layanan-surat.cetak.index')->with('error', 'Gagal Cetak: ' . $e->getMessage());
        }
    }

    public function liveSearchNik(Request $request) {
        $keyword = $request->keyword;
        if (empty($keyword)) return response()->json([]);

        $penduduk = Penduduk::where('nik', 'LIKE', $keyword . '%')
            ->orWhere('nama', 'LIKE', '%' . $keyword . '%')
            ->limit(10)
            ->get(['nik', 'nama']);

        return response()->json($penduduk);
    }

    public function getDataByNik($nik) {
        $penduduk = Penduduk::where('nik', $nik)->first();
        $desa = IdentitasDesa::first();

        if ($penduduk) {
            $keluarga = Keluarga::whereHas('anggota', function ($query) use ($nik) {
                $query->where('nik', $nik);
            })->with(['anggota'])->first();

            $dataKeluarga = null;
            if ($keluarga) {
                $dataKeluarga = [
                    'no_kk' => $keluarga->no_kk,
                    'alamat_kk' => $keluarga->alamat,
                    'kepala_keluarga' => $keluarga->getKepalaKeluarga() ? $keluarga->getKepalaKeluarga()->nama : '-',
                    'nik_kepala' => $keluarga->getKepalaKeluarga() ? $keluarga->getKepalaKeluarga()->nik : '-',
                    'daftar_anggota' => $keluarga->anggota
                ];
            }

            return response()->json([
                'success' => true,
                'penduduk' => $penduduk,
                'desa' => $desa,
                'keluarga' => $dataKeluarga
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Warga tidak ditemukan']);
    }

    public function getPendudukData($nik) {
        return $this->getDataByNik($nik);
    }

    public function store(Request $request) {
        $validated = $this->validateLetterRequest($request);
        $letter = Letter::create($validated);
        return redirect()->route('admin.layanan-surat.cetak.show', $letter->id);
    }

    public function show($id) {
        $letter = Letter::find($id) ?: ArsipSurat::findOrFail($id);
        return view('admin.layanan-surat.letters.show', compact('letter'));
    }

    public function cetak($id) {
        $arsip = ArsipSurat::findOrFail($id);
        $filePath = storage_path('app/public/' . $arsip->file_path);

        if (File::exists($filePath)) {
            return response()->file($filePath);
        }
        return back()->with('error', 'File PDF tidak ditemukan di server.');
    }

    private function validateLetterRequest(Request $request) {
        return $request->validate([
            'template_id'     => 'nullable|exists:surat_templates,id',
            'nama'            => 'required|string|max:255',
            'nik'             => 'required|string|max:50',
            // ... tambahkan field lainnya jika diperlukan ...
        ]);
    }

    private function generateAutoNomorSurat($templateId) {
        $template = SuratTemplate::find($templateId);
        $kodeKlasifikasi = $template->kode_klasifikasi ?? 'S-41';

        $desa = IdentitasDesa::first();
        $kodeWilayah = $desa ? ($desa->kode_wilayah ?? $desa->kode_desa ?? '9202172009') : '9202172009';

        $tahun = date('Y');
        $bulan = date('n');

        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $bulanRomawi = $romawi[$bulan];

        $jumlahSurat = ArsipSurat::whereYear('created_at', $tahun)->count();
        $nomorUrut = str_pad($jumlahSurat + 1, 3, '0', STR_PAD_LEFT);

        return "{$kodeKlasifikasi}/{$nomorUrut}/{$kodeWilayah}/{$bulanRomawi}/{$tahun}";
    }
}
