<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Perbaikan tabel wilayah
 *
 * Gap vs tweb_wil_clusterdesa OpenSID:
 *
 * TAMBAH:
 *   + nama          → nama/label wilayah cluster (opsional di OpenSID)
 *   + lat, lng      → koordinat GPS untuk peta (dipakai OpenSID)
 *   + deleted_at    → soft delete konsisten
 *
 * HAPUS:
 *   - jumlah_kk, laki_laki, perempuan, jumlah_penduduk
 *     Kolom-kolom ini tidak ada di OpenSID. Nilai tersebut dihitung
 *     secara dinamis dari join ke tabel penduduk/keluarga, bukan
 *     di-cache di tabel wilayah. Menyimpannya di sini rawan
 *     inkonsistensi data.
 *
 *     ⚠ PERHATIAN: Sebelum menjalankan migration ini, pastikan tidak
 *     ada query/controller yang bergantung pada kolom-kolom tsb.
 *     Ganti dengan query COUNT/JOIN ke tabel penduduk dan keluarga.
 *
 * UBAH:
 *   ~ ketua_rt, ketua_rw → tetap dipertahankan (OpenSID punya field
 *     kepala RT meski hanya satu field 'kepala'), tapi dijadikan
 *     nullable secara eksplisit.
 *
 * NOTE tentang tweb_wil_clusterdesa vs wilayah:
 *   OpenSID menyimpan Dusun, RW, RT dalam SATU baris tabel.
 *   Artinya RT 001/RW 001/Dusun Krajan adalah satu row.
 *   Struktur ini sudah benar di tabel wilayah kamu.
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('wilayah', function (Blueprint $table) {
            // -----------------------------------------------------------------
            // TAMBAH: nama wilayah / label cluster
            // Di OpenSID digunakan sebagai keterangan tambahan RT/RW
            // -----------------------------------------------------------------
            $table->string('nama', 100)
                ->nullable()
                ->after('rt')
                ->comment('Nama/label cluster, misal: Perumahan Griya Asri');

            // -----------------------------------------------------------------
            // TAMBAH: koordinat GPS untuk fitur peta OpenSID
            // -----------------------------------------------------------------
            $table->decimal('lat', 10, 7)
                ->nullable()
                ->after('ketua_rw')
                ->comment('Latitude koordinat GPS wilayah');

            $table->decimal('lng', 10, 7)
                ->nullable()
                ->after('lat')
                ->comment('Longitude koordinat GPS wilayah');

            // -----------------------------------------------------------------
            // TAMBAH: soft delete (konsisten dengan tabel lain)
            // -----------------------------------------------------------------
            $table->softDeletes()->after('updated_at');

            // -----------------------------------------------------------------
            // HAPUS: kolom denormalisasi — hitung dinamis dari query
            // -----------------------------------------------------------------
            $table->dropColumn([
                'jumlah_kk',
                'laki_laki',
                'perempuan',
                'jumlah_penduduk',
            ]);
        });
    }

    public function down(): void {
        Schema::table('wilayah', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['nama', 'lat', 'lng']);

            // Kembalikan kolom yang dihapus
            $table->integer('jumlah_kk')->default(0)->after('ketua_rw');
            $table->integer('laki_laki')->default(0)->after('jumlah_kk');
            $table->integer('perempuan')->default(0)->after('laki_laki');
            $table->integer('jumlah_penduduk')->default(0)->after('perempuan');
        });
    }
};
