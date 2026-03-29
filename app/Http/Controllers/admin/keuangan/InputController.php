<?php

namespace App\Http\Controllers\Admin\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunRekening;
use App\Models\AnggaranTahunan;
use App\Models\TransaksiKas; 
use App\Models\KasDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InputController extends Controller 
{
    // ================================================================
    // FITUR LAMA: INPUT TRANSAKSI KAS
    // ================================================================
    public function inputData() 
    {
        $recentTransactions = TransaksiKas::orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')->limit(10)->get();

        $kasDesa = KasDesa::all();

        return view('admin.keuangan.input-data', compact('recentTransactions', 'kasDesa'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tipe'    => 'required|in:masuk,keluar',
            'jumlah'  => 'required|numeric|min:1',
            'kas_id'  => 'required|exists:kas_desa,id',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'tipe.required'    => 'Jenis transaksi wajib dipilih',
            'jumlah.required'  => 'Jumlah wajib diisi',
            'jumlah.min'       => 'Jumlah minimal 1',
            'kas_id.required'  => 'Kas desa wajib dipilih',
            'kas_id.exists'    => 'Kas desa tidak valid',
        ]);

        try {
            TransaksiKas::create([
                'tanggal' => $request->tanggal,
                'tipe'    => $request->tipe,
                'jumlah'  => $request->jumlah,
                'kas_id'  => $request->kas_id,
            ]);

            return redirect()->route('admin.keuangan.input-data')
                ->with('success', 'Data transaksi berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->route('admin.keuangan.input-data')
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage())
                ->withInput();
        }
    }


    // ================================================================
    // FITUR BARU: TABEL INPUT ANGGARAN & REALISASI (Template)
    // ================================================================
    public function index(Request $request) 
    {
        $tahunDipilih = $request->get('tahun', 2026);
        $search = $request->get('search');

        $availableYears = AnggaranTahunan::select('tahun')
            ->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([2026]);
        }

        $query = AnggaranTahunan::with('akunRekening')->where('tahun', $tahunDipilih);

        if ($search) {
            $query->whereHas('akunRekening', function ($q) use ($search) {
                $q->where('uraian', 'like', "%{$search}%")
                  ->orWhere('kode_rekening', 'like', "%{$search}%");
            });
        }

        $data_anggaran = $query->get()->sortBy(function($item) {
            return $item->akunRekening->kode_rekening;
        })->values();

        return view('admin.keuangan.input-template', compact('data_anggaran', 'availableYears', 'tahunDipilih', 'search'));
    }

    public function tambahTemplate(Request $request) 
    {
        $request->validate(['tahun_baru' => 'required|numeric|digits:4']);
        $tahunBaru = $request->tahun_baru;

        if (AnggaranTahunan::where('tahun', $tahunBaru)->exists()) {
            return redirect()->back()->with('error', "Template untuk tahun {$tahunBaru} sudah tersedia.");
        }

        DB::beginTransaction();
        try {
            $semuaAkun = AkunRekening::all();
            $dataInsert = [];

            foreach ($semuaAkun as $akun) {
                $dataInsert[] = [
                    'akun_rekening_id' => $akun->id,
                    'tahun'            => $tahunBaru,
                    'anggaran'         => 0,
                    'realisasi'        => 0,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }

            AnggaranTahunan::insert($dataInsert);
            DB::commit();
            
            // PERBAIKAN DI SINI: route name disesuaikan dengan prefix admin
            return redirect()->route('admin.keuangan.input.index', ['tahun' => $tahunBaru])
                ->with('success', "Template Keuangan Tahun {$tahunBaru} berhasil dibuat.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function updateNominal(Request $request, $id) 
    {
        $request->validate([
            'anggaran'  => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
        ]);

        try {
            $anggaranTahunan = AnggaranTahunan::findOrFail($id);
            
            if (!$anggaranTahunan->akunRekening->is_editable) {
                 return redirect()->back()->with('error', 'Akun Induk tidak dapat diubah nominalnya.');
            }

            $anggaranTahunan->update([
                'anggaran'  => $request->anggaran,
                'realisasi' => $request->realisasi,
            ]);

            return redirect()->back()->with('success', 'Nominal anggaran berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}