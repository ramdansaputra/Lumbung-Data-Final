<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Perbaikan tabel keluarga
 *
 * Gap vs tweb_keluarga OpenSID:
 *
 * TAMBAH (CRITICAL):
 *   + nik_kepala    → NIK Kepala Keluarga. Di OpenSID, ini adalah relasi
 *                     ke tweb_penduduk. Tanpa ini, tidak bisa menentukan
 *                     siapa KK dari sebuah KK, tidak bisa cetak KK,
 *                     tidak bisa alur "Kepala Keluarga Meninggal", dll.
 *
 *   + status        → Status KK: aktif / tidak aktif
 *                     KK menjadi tidak aktif saat kepala keluarga pindah/meninggal
 *                     dan KK dimekarkan (pecah KK).
 *
 * CATATAN tentang klasifikasi_ekonomi & jenis_bantuan_aktif:
 *   Dua kolom ini TIDAK ada di tweb_keluarga OpenSID.
 *   Di OpenSID, data bantuan/ekonomi disimpan di tabel terpisah:
 *     - tweb_rtm        (Data Rumah Tangga Miskin / klasifikasi ekonomi)
 *     - tweb_bantuan_keluarga (program bantuan per keluarga)
 *   Kolom-kolom ini dipertahankan untuk saat ini agar tidak breaking,
 *   tapi perlu dipindah ke tabel bantuan/rtm tersendiri di iterasi berikutnya.
 *
 * TAMBAH (opsional, untuk kelengkapan OpenSID):
 *   + tgl_cetak_kk  → tanggal terakhir KK dicetak
 *   + alamat_kk     → alamat di KK (bisa beda dari alamat domisili)
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('keluarga', function (Blueprint $table) {
            // -----------------------------------------------------------------
            // CRITICAL: NIK Kepala Keluarga
            // Nullable dulu karena data lama belum punya nilai ini.
            // Setelah data dimigrasi, bisa di-set NOT NULL.
            //
            // Relasi ke penduduk.nik (string), bukan FK integer,
            // sesuai pola OpenSID yang join via NIK bukan via id.
            // Tapi kita juga siapkan penduduk_id (FK ke penduduk.id)
            // sebagai alternatif yang lebih efisien untuk query.
            // -----------------------------------------------------------------
            $table->string('nik_kepala', 16)
                ->nullable()
                ->after('no_kk')
                ->comment('NIK Kepala Keluarga — padanan nik di tweb_penduduk');

            // FK integer ke tabel penduduk (lebih efisien untuk JOIN)
            $table->foreignId('kepala_keluarga_id')
                ->nullable()
                ->after('nik_kepala')
                ->constrained('penduduk')
                ->nullOnDelete()
                ->comment('FK ke penduduk.id — Kepala Keluarga');

            // -----------------------------------------------------------------
            // CRITICAL: Status KK
            // 1 = Aktif, 0 = Tidak Aktif (setelah pecah KK / kepala meninggal)
            // -----------------------------------------------------------------
            $table->tinyInteger('status')
                ->default(1)
                ->after('kepala_keluarga_id')
                ->comment('1=Aktif, 0=Tidak Aktif (setelah pecah KK)');

            // -----------------------------------------------------------------
            // Tanggal cetak KK terakhir (ada di OpenSID)
            // -----------------------------------------------------------------
            $table->date('tgl_cetak_kk')
                ->nullable()
                ->after('tgl_terdaftar')
                ->comment('Tanggal terakhir KK dicetak');

            // -----------------------------------------------------------------
            // Index untuk pencarian cepat berdasarkan nik_kepala
            // -----------------------------------------------------------------
            $table->index('nik_kepala');
            $table->index('status');
        });
    }

    public function down(): void {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->dropForeign(['kepala_keluarga_id']);
            $table->dropIndex(['nik_kepala']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'nik_kepala',
                'kepala_keluarga_id',
                'status',
                'tgl_cetak_kk',
            ]);
        });
    }
};
