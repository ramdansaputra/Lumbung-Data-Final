<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Fix penduduk — lanjutan dari partial migration sebelumnya
 *
 * Kondisi tabel saat ini (hasil getColumnListing):
 * SUDAH ADA : id, keluarga_id, kk_level, nik, foto, tag_id_card, nama,
 *             nama_ayah, nama_ibu, jenis_kelamin, status_dasar,
 *             tempat_lahir, tanggal_lahir, golongan_darah, agama,
 *             pendidikan, pekerjaan, status_kawin, jenis_tambah,
 *             tgl_peristiwa, tgl_terdaftar, kewarganegaraan,
 *             no_telp, email, alamat, wilayah_id,
 *             created_at, updated_at, deleted_at
 *
 * YANG PERLU DIKERJAKAN:
 *   1. Tambah FK constraint yang belum terpasang (keluarga_id, kk_level)
 *   2. Tambah kolom 'status' (jenis penduduk: Tetap/TidakTetap/Pendatang)
 *   3. Fix jenis_tambah — hapus nilai 'meninggal'
 *   4. Tambah nik_ayah, nik_ibu
 *   5. Tambah semua kolom FK referensi (_id)
 *   6. Tambah kolom KTP el, perkawinan, kelahiran detail, kesehatan, dll
 *   7. Rename kolom varchar lama → _lama (deprecated)
 */
return new class extends Migration {
    public function up(): void {
        // =====================================================================
        // STEP 1 — Pasang FK yang belum terpasang
        // keluarga_id FK sudah terpasang dari migration sebelumnya, skip.
        // =====================================================================
        Schema::table('penduduk', function (Blueprint $table) {
            // FK kk_level → ref_shdk.id
            $table->foreign('kk_level')
                ->references('id')->on('ref_shdk')
                ->nullOnDelete();
        });

        // =====================================================================
        // STEP 2 — Tambah kolom yang belum ada
        // =====================================================================
        Schema::table('penduduk', function (Blueprint $table) {

            // -----------------------------------------------------------------
            // [P1] Status jenis penduduk
            // -----------------------------------------------------------------
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->after('status_dasar')
                ->comment('Jenis penduduk: 1=Tetap, 2=TidakTetap, 3=Pendatang');

            // -----------------------------------------------------------------
            // [P2] NIK Ayah & Ibu
            // -----------------------------------------------------------------
            $table->string('nik_ayah', 16)
                ->nullable()
                ->after('nama_ibu')
                ->comment('NIK Ayah — padanan ayah_nik OpenSID');

            $table->string('nik_ibu', 16)
                ->nullable()
                ->after('nik_ayah')
                ->comment('NIK Ibu — padanan ibu_nik OpenSID');

            // -----------------------------------------------------------------
            // [P2] Kolom FK referensi (menggantikan varchar)
            // -----------------------------------------------------------------
            $table->foreignId('agama_id')
                ->nullable()
                ->after('agama')
                ->constrained('ref_agama')->nullOnDelete()
                ->comment('FK ke ref_agama');

            $table->unsignedBigInteger('pendidikan_kk_id')
                ->nullable()
                ->after('pendidikan')
                ->comment('FK ke ref_pendidikan — pendidikan dalam KK');
            $table->foreign('pendidikan_kk_id')
                ->references('id')->on('ref_pendidikan')->nullOnDelete();

            $table->unsignedBigInteger('pendidikan_sedang_id')
                ->nullable()
                ->after('pendidikan_kk_id')
                ->comment('FK ke ref_pendidikan — sedang ditempuh');
            $table->foreign('pendidikan_sedang_id')
                ->references('id')->on('ref_pendidikan')->nullOnDelete();

            $table->foreignId('pekerjaan_id')
                ->nullable()
                ->after('pekerjaan')
                ->constrained('ref_pekerjaan')->nullOnDelete()
                ->comment('FK ke ref_pekerjaan');

            $table->foreignId('status_kawin_id')
                ->nullable()
                ->after('status_kawin')
                ->constrained('ref_status_kawin')->nullOnDelete()
                ->comment('FK ke ref_status_kawin');

            $table->foreignId('golongan_darah_id')
                ->nullable()
                ->after('golongan_darah')
                ->constrained('ref_golongan_darah')->nullOnDelete()
                ->comment('FK ke ref_golongan_darah');

            $table->foreignId('warganegara_id')
                ->nullable()
                ->after('kewarganegaraan')
                ->constrained('ref_warganegara')->nullOnDelete()
                ->comment('FK ke ref_warganegara');

            // -----------------------------------------------------------------
            // [P2] Data KTP Elektronik
            // -----------------------------------------------------------------
            $table->tinyInteger('ktp_el')
                ->nullable()
                ->after('warganegara_id')
                ->comment('0=Tidak, 1=Ada KTP-el');

            $table->tinyInteger('status_rekam')
                ->nullable()
                ->after('ktp_el')
                ->comment('Status perekaman KTP-el di Disdukcapil');

            $table->string('tempat_cetak_ktp', 100)
                ->nullable()
                ->after('status_rekam');

            $table->date('tanggal_cetak_ktp')
                ->nullable()
                ->after('tempat_cetak_ktp');

            // -----------------------------------------------------------------
            // [P2] Detail Perkawinan
            // -----------------------------------------------------------------
            $table->string('akta_perkawinan', 100)
                ->nullable()
                ->after('status_kawin_id')
                ->comment('Nomor Akta Nikah/Perkawinan');

            $table->date('tanggal_perkawinan')
                ->nullable()
                ->after('akta_perkawinan');

            $table->string('akta_perceraian', 100)
                ->nullable()
                ->after('tanggal_perkawinan')
                ->comment('Diisi = Cerai Hidup Tercatat');

            $table->date('tanggal_perceraian')
                ->nullable()
                ->after('akta_perceraian')
                ->comment('Diisi = Cerai Hidup Tercatat');

            // -----------------------------------------------------------------
            // [P3] Data Kelahiran Detail
            // -----------------------------------------------------------------
            $table->time('waktu_lahir')
                ->nullable()
                ->after('tanggal_lahir');

            $table->string('tempat_dilahirkan', 100)
                ->nullable()
                ->after('waktu_lahir')
                ->comment('RS/Puskesmas/Rumah/dll');

            $table->string('jenis_kelahiran', 50)
                ->nullable()
                ->after('tempat_dilahirkan')
                ->comment('Tunggal/Kembar 2/Kembar 3/dst');

            $table->tinyInteger('kelahiran_anak_ke')
                ->nullable()
                ->after('jenis_kelahiran');

            $table->string('penolong_kelahiran', 50)
                ->nullable()
                ->after('kelahiran_anak_ke')
                ->comment('Dokter/Bidan/Dukun/dll');

            $table->decimal('berat_lahir', 5, 2)
                ->nullable()
                ->after('penolong_kelahiran')
                ->comment('kg');

            $table->decimal('panjang_lahir', 5, 2)
                ->nullable()
                ->after('berat_lahir')
                ->comment('cm');

            $table->string('akta_lahir', 100)
                ->nullable()
                ->after('panjang_lahir');

            // -----------------------------------------------------------------
            // [P3] Kesehatan
            // -----------------------------------------------------------------
            $table->foreignId('cacat_id')
                ->nullable()
                ->after('golongan_darah_id')
                ->constrained('ref_cacat')->nullOnDelete();

            $table->foreignId('sakit_menahun_id')
                ->nullable()
                ->after('cacat_id')
                ->constrained('ref_sakit_menahun')->nullOnDelete();

            $table->foreignId('cara_kb_id')
                ->nullable()
                ->after('sakit_menahun_id')
                ->constrained('ref_cara_kb')->nullOnDelete();

            $table->tinyInteger('hamil')
                ->default(0)
                ->after('cara_kb_id')
                ->comment('0=Tidak, 1=Hamil');

            // -----------------------------------------------------------------
            // [P3] Asuransi
            // -----------------------------------------------------------------
            $table->foreignId('asuransi_id')
                ->nullable()
                ->after('hamil')
                ->constrained('ref_asuransi')->nullOnDelete();

            $table->string('no_asuransi', 100)
                ->nullable()
                ->after('asuransi_id');

            // -----------------------------------------------------------------
            // [P3] Dokumen Keimigrasian
            // -----------------------------------------------------------------
            $table->string('dokumen_pasport', 100)->nullable()->after('no_asuransi');
            $table->date('tanggal_akhir_paspor')->nullable()->after('dokumen_pasport');
            $table->string('dokumen_kitas', 100)->nullable()->after('tanggal_akhir_paspor');
            $table->string('negara_asal', 100)->nullable()->after('dokumen_kitas');

            // -----------------------------------------------------------------
            // [P3] Bahasa
            // -----------------------------------------------------------------
            $table->foreignId('bahasa_id')
                ->nullable()
                ->after('negara_asal')
                ->constrained('ref_bahasa')->nullOnDelete();

            // -----------------------------------------------------------------
            // [P3] Alamat & catatan
            // -----------------------------------------------------------------
            $table->text('alamat_sebelumnya')->nullable()->after('alamat');
            $table->string('no_kk_sebelumnya', 16)->nullable()->after('alamat_sebelumnya');
            $table->text('keterangan')->nullable()->after('no_kk_sebelumnya');

            // -----------------------------------------------------------------
            // Index tambahan
            // -----------------------------------------------------------------
            $table->index('status');
            $table->index('status_dasar');
            $table->index('nik_ayah');
            $table->index('nik_ibu');
        });

        // =====================================================================
        // STEP 3 — Fix jenis_tambah: ganti 'meninggal' → 'masuk', lalu
        //          ubah definisi enum supaya tidak bisa input 'meninggal' lagi
        // =====================================================================
        DB::statement("UPDATE penduduk SET jenis_tambah = 'masuk' WHERE jenis_tambah = 'meninggal'");

        DB::statement("ALTER TABLE penduduk MODIFY COLUMN jenis_tambah ENUM('lahir','masuk') NOT NULL DEFAULT 'lahir'");

        // =====================================================================
        // STEP 4 — Rename kolom varchar lama → _lama (deprecated)
        // =====================================================================
        Schema::table('penduduk', function (Blueprint $table) {
            $table->renameColumn('agama',          'agama_lama');
            $table->renameColumn('pendidikan',     'pendidikan_lama');
            $table->renameColumn('pekerjaan',      'pekerjaan_lama');
            $table->renameColumn('status_kawin',   'status_kawin_lama');
            $table->renameColumn('golongan_darah', 'golongan_darah_lama');
            $table->renameColumn('kewarganegaraan', 'kewarganegaraan_lama');
        });
    }

    public function down(): void {
        Schema::table('penduduk', function (Blueprint $table) {
            $table->renameColumn('agama_lama',           'agama');
            $table->renameColumn('pendidikan_lama',      'pendidikan');
            $table->renameColumn('pekerjaan_lama',       'pekerjaan');
            $table->renameColumn('status_kawin_lama',    'status_kawin');
            $table->renameColumn('golongan_darah_lama',  'golongan_darah');
            $table->renameColumn('kewarganegaraan_lama', 'kewarganegaraan');
        });

        Schema::table('penduduk', function (Blueprint $table) {
            $table->dropForeign(['keluarga_id']);
            $table->dropForeign(['kk_level']);
            $table->dropForeign(['agama_id']);
            $table->dropForeign(['pendidikan_kk_id']);
            $table->dropForeign(['pendidikan_sedang_id']);
            $table->dropForeign(['pekerjaan_id']);
            $table->dropForeign(['status_kawin_id']);
            $table->dropForeign(['golongan_darah_id']);
            $table->dropForeign(['warganegara_id']);
            $table->dropForeign(['cacat_id']);
            $table->dropForeign(['sakit_menahun_id']);
            $table->dropForeign(['cara_kb_id']);
            $table->dropForeign(['asuransi_id']);
            $table->dropForeign(['bahasa_id']);

            $table->dropIndex(['status']);
            $table->dropIndex(['status_dasar']);
            $table->dropIndex(['nik_ayah']);
            $table->dropIndex(['nik_ibu']);

            $table->dropColumn([
                'status',
                'nik_ayah',
                'nik_ibu',
                'agama_id',
                'pendidikan_kk_id',
                'pendidikan_sedang_id',
                'pekerjaan_id',
                'status_kawin_id',
                'golongan_darah_id',
                'warganegara_id',
                'ktp_el',
                'status_rekam',
                'tempat_cetak_ktp',
                'tanggal_cetak_ktp',
                'akta_perkawinan',
                'tanggal_perkawinan',
                'akta_perceraian',
                'tanggal_perceraian',
                'waktu_lahir',
                'tempat_dilahirkan',
                'jenis_kelahiran',
                'kelahiran_anak_ke',
                'penolong_kelahiran',
                'berat_lahir',
                'panjang_lahir',
                'akta_lahir',
                'cacat_id',
                'sakit_menahun_id',
                'cara_kb_id',
                'hamil',
                'asuransi_id',
                'no_asuransi',
                'dokumen_pasport',
                'tanggal_akhir_paspor',
                'dokumen_kitas',
                'negara_asal',
                'bahasa_id',
                'alamat_sebelumnya',
                'no_kk_sebelumnya',
                'keterangan',
            ]);
        });

        DB::statement("ALTER TABLE penduduk MODIFY COLUMN jenis_tambah ENUM('lahir','masuk','meninggal') NOT NULL DEFAULT 'lahir'");
    }
};
