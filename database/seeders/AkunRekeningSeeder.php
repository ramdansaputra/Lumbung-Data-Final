<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AkunRekening;

class AkunRekeningSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // PENDAPATAN
            ['kode' => '4', 'uraian' => 'PENDAPATAN', 'edit' => false],
            ['kode' => '4.1', 'uraian' => 'Pendapatan Asli Desa', 'edit' => false],
            ['kode' => '4.1.1', 'uraian' => 'Hasil Usaha', 'edit' => true],
            ['kode' => '4.1.2', 'uraian' => 'Hasil Aset', 'edit' => true],
            ['kode' => '4.1.3', 'uraian' => 'Swadaya, Partisipasi dan Gotong Royong', 'edit' => true],
            ['kode' => '4.1.4', 'uraian' => 'Lain-lain Pendapatan Asli Desa', 'edit' => true],
            ['kode' => '4.2', 'uraian' => 'Transfer', 'edit' => false],
            ['kode' => '4.2.1', 'uraian' => 'Dana Desa', 'edit' => true],
            ['kode' => '4.2.2', 'uraian' => 'Bagian dari Hasil Pajak dan Retribusi Daerah Kabupaten/Kota', 'edit' => true],
            ['kode' => '4.2.3', 'uraian' => 'Alokasi Dana Desa', 'edit' => true],
            ['kode' => '4.2.4', 'uraian' => 'Bantuan Keuangan Provinsi', 'edit' => true],
            ['kode' => '4.2.5', 'uraian' => 'Bantuan Keuangan APBD Kabupaten/Kota', 'edit' => true],
            ['kode' => '4.3', 'uraian' => 'Pendapatan Lain-lain', 'edit' => false],
            ['kode' => '4.3.1', 'uraian' => 'Penerimaan dari Hasil Kerjasama antar Desa', 'edit' => true],
            ['kode' => '4.3.2', 'uraian' => 'Penerimaan dari Hasil Kerjasama Desa dengan Pihak Ketiga', 'edit' => true],
            ['kode' => '4.3.3', 'uraian' => 'Penerimaan dari Bantuan Perusahaan yang Berlokasi di Desa', 'edit' => true],
            ['kode' => '4.3.4', 'uraian' => 'Hibah dan Sumbangan dari Pihak Ketiga', 'edit' => true],
            ['kode' => '4.3.5', 'uraian' => 'Koreksi Kesalahan Belanja Tahun-Tahun Anggaran Sebelumnya yang Mengakibatkan Penerimaan di Kas Desa pada Tahun Anggaran Berjalan', 'edit' => true],
            ['kode' => '4.3.6', 'uraian' => 'Bunga Bank', 'edit' => true],
            ['kode' => '4.3.9', 'uraian' => 'Lain-lain Pendapatan Desa yang Sah', 'edit' => true],

            // BELANJA
            ['kode' => '5', 'uraian' => 'BELANJA', 'edit' => false],
            ['kode' => '5.1', 'uraian' => 'BIDANG PENYELENGGARAN PEMERINTAHAN DESA', 'edit' => false],
            ['kode' => '5.1.1', 'uraian' => 'Penyelenggaran Belanja Siltap, Tunjangan dan Operasional Pemerintah Desa', 'edit' => true],
            ['kode' => '5.1.2', 'uraian' => 'Sarana dan Prasaran Pemerintah Desa', 'edit' => true],
            ['kode' => '5.1.3', 'uraian' => 'Administrasi Kependudukan, Pencatatan Sipil, Statistik dan Kearsipan', 'edit' => true],
            ['kode' => '5.1.4', 'uraian' => 'Tata Praja Pemerintahan, Perencanaan, Keuangan', 'edit' => true],
            ['kode' => '5.1.5', 'uraian' => 'Sub Bidang Pertanahan', 'edit' => true],
            ['kode' => '5.2', 'uraian' => 'BIDANG PELAKSANAAN PEMBANGUNAN DESA', 'edit' => false],
            ['kode' => '5.2.1', 'uraian' => 'Sub Bidang Pendidikan', 'edit' => true],
            ['kode' => '5.2.2', 'uraian' => 'Sub Bidang Kesehatan', 'edit' => true],
            ['kode' => '5.2.3', 'uraian' => 'Sub Bidang Pekerjaan Umum dan Penataan Ruang', 'edit' => true],
            ['kode' => '5.2.4', 'uraian' => 'Sub Bidang Kawasan Pemukiman', 'edit' => true],
            ['kode' => '5.2.5', 'uraian' => 'Sub Bidang Kehutanan dan Lingkungan Hidup', 'edit' => true],
            ['kode' => '5.2.6', 'uraian' => 'Sub Bidang Perhubungan, Komunikasi dan Informatika', 'edit' => true],
            ['kode' => '5.2.7', 'uraian' => 'Sub Bidang Energi dan Sumber Daya Mineral', 'edit' => true],
            ['kode' => '5.2.8', 'uraian' => 'Sub Bidang Pariwisata', 'edit' => true],
            ['kode' => '5.3', 'uraian' => 'BIDANG PEMBINAAN KEMASYARAKATAN', 'edit' => false],
            ['kode' => '5.3.1', 'uraian' => 'Ketenteraman, Ketertiban Umum, dan Perlindungan Masyarakat', 'edit' => true],
            ['kode' => '5.3.2', 'uraian' => 'Kebudayaan dan Keagamaan', 'edit' => true],
            ['kode' => '5.3.3', 'uraian' => 'Kepemudaan dan Olah Raga', 'edit' => true],
            ['kode' => '5.3.4', 'uraian' => 'Kelembagaan Masyarakat', 'edit' => true],
            ['kode' => '5.4', 'uraian' => 'BIDANG PEMBERDAYAAN MASYARAKAT', 'edit' => false],
            ['kode' => '5.4.1', 'uraian' => 'Sub Bidang Kelautan dan Perikanan', 'edit' => true],
            ['kode' => '5.4.2', 'uraian' => 'Sub Bidang Pertanian dan Peternakan', 'edit' => true],
            ['kode' => '5.4.3', 'uraian' => 'Sub Bidang Peningkatan Kapasita Aparatur Desa', 'edit' => true],
            ['kode' => '5.4.4', 'uraian' => 'Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga', 'edit' => true],
            ['kode' => '5.4.5', 'uraian' => 'Koperasi, Usaha Mikro Kecil dan Menegah (UMKM)', 'edit' => true],
            ['kode' => '5.4.6', 'uraian' => 'Dukungan Penanaman Modal', 'edit' => true],
            ['kode' => '5.4.7', 'uraian' => 'Perdagangan dan Perindustrian', 'edit' => true],
            ['kode' => '5.5', 'uraian' => 'PENAGGULANGAN BENCANA, KEADAAN DARURAT DAN MENDESAK', 'edit' => false],
            ['kode' => '5.5.1', 'uraian' => 'Penanggulangan Bencana', 'edit' => true],
            ['kode' => '5.5.2', 'uraian' => 'Keadaan Darurat', 'edit' => true],
            ['kode' => '5.5.3', 'uraian' => 'Mendesak', 'edit' => true],

            // PEMBIAYAAN
            ['kode' => '6', 'uraian' => 'PEMBIAYAAN', 'edit' => false],
            ['kode' => '6.1', 'uraian' => 'Penerimaan Pembiayaan', 'edit' => false],
            ['kode' => '6.1.1', 'uraian' => 'SILPA Tahun Sebelumnya', 'edit' => true],
            ['kode' => '6.1.2', 'uraian' => 'Pencairan Dana Cadangan', 'edit' => true],
            ['kode' => '6.1.3', 'uraian' => 'Hasil Penjualan Kekayaan Desa yang Dipisahkan', 'edit' => true],
            ['kode' => '6.1.9', 'uraian' => 'Penerimaan Pembiayaan Lainnya', 'edit' => true],
            ['kode' => '6.2', 'uraian' => 'Pengeluaran Pembiayaan', 'edit' => false],
            ['kode' => '6.2.1', 'uraian' => 'Pembentukan Dana Cadangan', 'edit' => true],
            ['kode' => '6.2.2', 'uraian' => 'Penyertaan Modal Desa', 'edit' => true],
            ['kode' => '6.2.9', 'uraian' => 'Pengeluaran Pembiayaan Lainnya', 'edit' => true],
        ];

        // Looping untuk eksekusi ke tabel
        foreach ($data as $item) {
            AkunRekening::updateOrCreate(
                ['kode_rekening' => $item['kode']], // Hindari duplikat jika di-run ulang
                [
                    'uraian' => $item['uraian'],
                    'is_editable' => $item['edit'],
                ]
            );
        }
    }
}