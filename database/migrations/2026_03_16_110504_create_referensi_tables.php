<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration: Tabel Referensi / Master Data
 *
 * Tabel-tabel ini adalah padanan dari tabel referensi OpenSID:
 *   tweb_agama             → ref_agama
 *   tweb_pendidikan        → ref_pendidikan
 *   tweb_pekerjaan         → ref_pekerjaan
 *   ref_golongan_darah     → ref_golongan_darah
 *   tweb_status_kawin      → ref_status_kawin
 *   tweb_penduduk_hubungan → ref_shdk  (Status Hubungan Dalam Keluarga / kk_level)
 *   tweb_warganegara       → ref_warganegara
 *   tweb_cacat             → ref_cacat
 *   tweb_sakit_menahun     → ref_sakit_menahun
 *   tweb_cara_kb           → ref_cara_kb
 *   ref_jenis_asuransi     → ref_asuransi
 *   tweb_bahasa            → ref_bahasa
 *
 * CATATAN: Setelah menjalankan migration ini, jalankan seeder:
 *   php artisan db:seed --class=ReferensiSeeder
 * untuk mengisi data master sesuai kode baku OpenSID/Kemendagri.
 */
return new class extends Migration {
    public function up(): void {
        // =====================================================================
        // ref_agama — padanan tweb_agama
        // =====================================================================
        Schema::create('ref_agama', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();   // ISLAM, KRISTEN, dst
            $table->timestamps();
        });

        DB::table('ref_agama')->insert([
            ['id' => 1, 'nama' => 'ISLAM',           'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'KRISTEN',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'KATHOLIK',         'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'HINDU',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'BUDHA',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'KONGHUCU',         'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama' => 'KEPERCAYAAN',      'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_pendidikan — padanan tweb_pendidikan
        // Digunakan dua kali di penduduk: pendidikan_kk_id dan pendidikan_sedang_id
        // =====================================================================
        Schema::create('ref_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->boolean('dalam_kk')->default(true); // tampil di dropdown "Pendidikan dalam KK"
            $table->boolean('sedang')->default(true);   // tampil di dropdown "Sedang ditempuh"
            $table->timestamps();
        });

        DB::table('ref_pendidikan')->insert([
            ['id' => 1,  'nama' => 'TIDAK/BELUM SEKOLAH',       'dalam_kk' => 1, 'sedang' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,  'nama' => 'BELUM TAMAT SD/SEDERAJAT',  'dalam_kk' => 1, 'sedang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,  'nama' => 'TAMAT SD/SEDERAJAT',        'dalam_kk' => 1, 'sedang' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,  'nama' => 'SLTP/SEDERAJAT',            'dalam_kk' => 1, 'sedang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'nama' => 'SLTA/SEDERAJAT',            'dalam_kk' => 1, 'sedang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6,  'nama' => 'DIPLOMA I/II',              'dalam_kk' => 1, 'sedang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,  'nama' => 'AKADEMI/DIPLOMA III/S. MUDA', 'dalam_kk' => 1, 'sedang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8,  'nama' => 'DIPLOMA IV/STRATA I',       'dalam_kk' => 1, 'sedang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9,  'nama' => 'STRATA II',                 'dalam_kk' => 1, 'sedang' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama' => 'STRATA III',                'dalam_kk' => 1, 'sedang' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_pekerjaan — padanan tweb_pekerjaan
        // =====================================================================
        Schema::create('ref_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        DB::table('ref_pekerjaan')->insert([
            ['id' => 1,  'nama' => 'BELUM/TIDAK BEKERJA',              'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,  'nama' => 'MENGURUS RUMAH TANGGA',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,  'nama' => 'PELAJAR/MAHASISWA',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,  'nama' => 'PENSIUNAN',                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'nama' => 'PEGAWAI NEGERI SIPIL',             'created_at' => now(), 'updated_at' => now()],
            ['id' => 6,  'nama' => 'TENTARA NASIONAL INDONESIA',       'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,  'nama' => 'KEPOLISIAN RI',                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 8,  'nama' => 'PERDAGANGAN',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 9,  'nama' => 'PETANI/PEKEBUN',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama' => 'PETERNAK',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama' => 'NELAYAN/PERIKANAN',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'nama' => 'INDUSTRI',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'nama' => 'KONSTRUKSI',                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'nama' => 'TRANSPORTASI',                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'nama' => 'KARYAWAN SWASTA',                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'nama' => 'KARYAWAN BUMN',                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'nama' => 'KARYAWAN BUMD',                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'nama' => 'KARYAWAN HONORER',                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'nama' => 'BURUH HARIAN LEPAS',               'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'nama' => 'BURUH TANI/PERKEBUNAN',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'nama' => 'BURUH NELAYAN/PERIKANAN',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'nama' => 'BURUH PETERNAKAN',                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'nama' => 'PEMBANTU RUMAH TANGGA',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'nama' => 'TUKANG CUKUR',                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'nama' => 'TUKANG LISTRIK',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'nama' => 'TUKANG BATU',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'nama' => 'TUKANG KAYU',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'nama' => 'TUKANG SOL SEPATU',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'nama' => 'TUKANG LAS/PANDAI BESI',           'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'nama' => 'TUKANG JAHIT',                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'nama' => 'TUKANG GIGI',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'nama' => 'PENATA RIAS',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'nama' => 'PENATA BUSANA',                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'nama' => 'PENATA RAMBUT',                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 35, 'nama' => 'MEKANIK',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'nama' => 'SENIMAN',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'nama' => 'TABIB',                            'created_at' => now(), 'updated_at' => now()],
            ['id' => 38, 'nama' => 'PARAJI',                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 39, 'nama' => 'PERANCANG BUSANA',                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 40, 'nama' => 'PENTERJEMAH',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 41, 'nama' => 'IMAM MESJID',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 42, 'nama' => 'PENDETA',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 43, 'nama' => 'PASTOR',                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 44, 'nama' => 'WARTAWAN',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 45, 'nama' => 'USTADZ/MUBALIGH',                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 46, 'nama' => 'JURU MASAK',                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 47, 'nama' => 'PROMOTOR ACARA',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 48, 'nama' => 'ANGGOTA DPR-RI',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 49, 'nama' => 'ANGGOTA DPD',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 50, 'nama' => 'ANGGOTA BPK',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 51, 'nama' => 'PRESIDEN',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 52, 'nama' => 'WAKIL PRESIDEN',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 53, 'nama' => 'ANGGOTA MAHKAMAH KONSTITUSI',      'created_at' => now(), 'updated_at' => now()],
            ['id' => 54, 'nama' => 'ANGGOTA KABINET/KEMENTERIAN',      'created_at' => now(), 'updated_at' => now()],
            ['id' => 55, 'nama' => 'DUTA BESAR',                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 56, 'nama' => 'GUBERNUR',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 57, 'nama' => 'WAKIL GUBERNUR',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 58, 'nama' => 'BUPATI',                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 59, 'nama' => 'WAKIL BUPATI',                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 60, 'nama' => 'WALIKOTA',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 61, 'nama' => 'WAKIL WALIKOTA',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 62, 'nama' => 'ANGGOTA DPRD PROVINSI',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 63, 'nama' => 'ANGGOTA DPRD KABUPATEN/KOTA',      'created_at' => now(), 'updated_at' => now()],
            ['id' => 64, 'nama' => 'DOSEN',                            'created_at' => now(), 'updated_at' => now()],
            ['id' => 65, 'nama' => 'GURU',                             'created_at' => now(), 'updated_at' => now()],
            ['id' => 66, 'nama' => 'PILOT',                            'created_at' => now(), 'updated_at' => now()],
            ['id' => 67, 'nama' => 'PENGACARA',                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 68, 'nama' => 'NOTARIS',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 69, 'nama' => 'ARSITEK',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 70, 'nama' => 'AKUNTAN',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 71, 'nama' => 'KONSULTAN',                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 72, 'nama' => 'DOKTER',                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 73, 'nama' => 'BIDAN',                            'created_at' => now(), 'updated_at' => now()],
            ['id' => 74, 'nama' => 'PERAWAT',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 75, 'nama' => 'APOTEKER',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 76, 'nama' => 'PSIKIATER/PSIKOLOG',               'created_at' => now(), 'updated_at' => now()],
            ['id' => 77, 'nama' => 'PENYIAR TELEVISI',                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 78, 'nama' => 'PENYIAR RADIO',                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 79, 'nama' => 'PELAUT',                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 80, 'nama' => 'PENELITI',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 81, 'nama' => 'SOPIR',                            'created_at' => now(), 'updated_at' => now()],
            ['id' => 82, 'nama' => 'PIALANG',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 83, 'nama' => 'PARANORMAL',                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 84, 'nama' => 'PEDAGANG',                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 85, 'nama' => 'PERANGKAT DESA',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 86, 'nama' => 'KEPALA DESA',                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 87, 'nama' => 'BIARAWATI',                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 88, 'nama' => 'WIRASWASTA',                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 90, 'nama' => 'PEKERJA MIGRAN',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 99, 'nama' => 'LAINNYA',                          'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_golongan_darah — padanan ref_golongan_darah OpenSID
        // =====================================================================
        Schema::create('ref_golongan_darah', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 10)->unique();
            $table->timestamps();
        });

        DB::table('ref_golongan_darah')->insert([
            ['id' => 1,  'nama' => 'A',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,  'nama' => 'B',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,  'nama' => 'AB',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,  'nama' => 'O',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'nama' => 'A+',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 6,  'nama' => 'A-',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,  'nama' => 'B+',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 8,  'nama' => 'B-',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 9,  'nama' => 'AB+',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama' => 'AB-',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama' => 'O+',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'nama' => 'O-',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'nama' => 'TIDAK TAHU', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_status_kawin — padanan tweb_status_kawin OpenSID
        // =====================================================================
        Schema::create('ref_status_kawin', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        DB::table('ref_status_kawin')->insert([
            ['id' => 1, 'nama' => 'BELUM KAWIN',                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'KAWIN TERCATAT',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'KAWIN BELUM TERCATAT',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'CERAI HIDUP TERCATAT',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'CERAI HIDUP TIDAK TERCATAT',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'CERAI MATI',                    'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_shdk — Status Hubungan Dalam Keluarga
        // Padanan tweb_penduduk_hubungan OpenSID (digunakan sebagai kk_level)
        // =====================================================================
        Schema::create('ref_shdk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        DB::table('ref_shdk')->insert([
            ['id' => 1,  'nama' => 'KEPALA KELUARGA',     'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,  'nama' => 'SUAMI',               'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,  'nama' => 'ISTRI',               'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,  'nama' => 'ANAK',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'nama' => 'MENANTU',             'created_at' => now(), 'updated_at' => now()],
            ['id' => 6,  'nama' => 'CUCU',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,  'nama' => 'ORANG TUA',           'created_at' => now(), 'updated_at' => now()],
            ['id' => 8,  'nama' => 'MERTUA',              'created_at' => now(), 'updated_at' => now()],
            ['id' => 9,  'nama' => 'FAMILI LAIN',         'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama' => 'PEMBANTU',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama' => 'LAINNYA',             'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_warganegara — padanan tweb_warganegara
        // =====================================================================
        Schema::create('ref_warganegara', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique();
            $table->timestamps();
        });

        DB::table('ref_warganegara')->insert([
            ['id' => 1, 'nama' => 'WNI',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'WNA',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'DWIKEWARGANEGARAAN', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_cacat — padanan tweb_cacat
        // =====================================================================
        Schema::create('ref_cacat', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        DB::table('ref_cacat')->insert([
            ['id' => 1, 'nama' => 'TIDAK ADA',           'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'FISIK',               'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'NETRA/BUTA',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'RUNGU/WICARA',        'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'MENTAL/JIWA',         'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'FISIK DAN MENTAL',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama' => 'LAINNYA',             'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_sakit_menahun — padanan tweb_sakit_menahun
        // =====================================================================
        Schema::create('ref_sakit_menahun', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        DB::table('ref_sakit_menahun')->insert([
            ['id' => 1,  'nama' => 'TIDAK ADA',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,  'nama' => 'ASMA',               'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,  'nama' => 'TBC',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,  'nama' => 'DARAH TINGGI',       'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'nama' => 'DIABETES',           'created_at' => now(), 'updated_at' => now()],
            ['id' => 6,  'nama' => 'KANKER',             'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,  'nama' => 'STROKE',             'created_at' => now(), 'updated_at' => now()],
            ['id' => 8,  'nama' => 'JANTUNG',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 9,  'nama' => 'GAGAL GINJAL',       'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama' => 'TALASEMIA',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 99, 'nama' => 'LAINNYA',            'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_cara_kb — padanan tweb_cara_kb
        // =====================================================================
        Schema::create('ref_cara_kb', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        DB::table('ref_cara_kb')->insert([
            ['id' => 1, 'nama' => 'TIDAK KB',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'IUD/AKDR',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'SUNTIK',              'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'PIL',                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'KONDOM',              'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'IMPLAN',              'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama' => 'MOP/VASEKTOMI',       'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama' => 'MOW/TUBEKTOMI',       'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_asuransi — padanan ref_jenis_asuransi OpenSID
        // =====================================================================
        Schema::create('ref_asuransi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        DB::table('ref_asuransi')->insert([
            ['id' => 1, 'nama' => 'TIDAK ADA',           'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'BPJS KESEHATAN',      'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'ASURANSI SWASTA',     'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'JAMSOSTEK',           'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'ASTEK',               'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'ASKES',               'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // ref_bahasa — padanan tweb_bahasa
        // =====================================================================
        Schema::create('ref_bahasa', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        DB::table('ref_bahasa')->insert([
            ['id' => 1, 'nama' => 'INDONESIA',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'JAWA',         'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'SUNDA',        'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'MELAYU',       'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'MADURA',       'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'MINANGKABAU',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama' => 'BATAK',        'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama' => 'BUGIS',        'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nama' => 'BALI',         'created_at' => now(), 'updated_at' => now()],
            ['id' => 99, 'nama' => 'LAINNYA',     'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('ref_bahasa');
        Schema::dropIfExists('ref_asuransi');
        Schema::dropIfExists('ref_cara_kb');
        Schema::dropIfExists('ref_sakit_menahun');
        Schema::dropIfExists('ref_cacat');
        Schema::dropIfExists('ref_warganegara');
        Schema::dropIfExists('ref_shdk');
        Schema::dropIfExists('ref_status_kawin');
        Schema::dropIfExists('ref_golongan_darah');
        Schema::dropIfExists('ref_pekerjaan');
        Schema::dropIfExists('ref_pendidikan');
        Schema::dropIfExists('ref_agama');
    }
};
