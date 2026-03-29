<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Fix penduduk — lanjutan dari partial migration sebelumnya
 */
return new class extends Migration {
    public function up(): void {
        // =====================================================================
        // STEP 1 & 2 — Tambah kolom dan pasang FK secara aman (TANPA ->after)
        // =====================================================================
        Schema::table('penduduk', function (Blueprint $table) {
            
            // Cek & Buat kk_level
            if (!Schema::hasColumn('penduduk', 'kk_level')) {
                $table->unsignedBigInteger('kk_level')->nullable();
                $table->foreign('kk_level')->references('id')->on('ref_shdk')->nullOnDelete();
            }

            // [P1] Status jenis penduduk
            if (!Schema::hasColumn('penduduk', 'status')) {
                $table->unsignedTinyInteger('status')->default(1)->comment('Jenis penduduk: 1=Tetap, 2=TidakTetap, 3=Pendatang');
                $table->index('status');
            }
            
            // Hanya buat index kalau kolomnya beneran ada
            if (Schema::hasColumn('penduduk', 'status_dasar')) {
                // Ignore jika index sudah ada (MySQL akan skip secara otomatis jika pakai constraint nama, 
                // tapi amannya kita biarkan tanpa exception di level ini, atau abaikan saja jika ragu).
                // Kita coba index, kalau sudah ada ya sudah.
                try {
                    $table->index('status_dasar');
                } catch (\Exception $e) { }
            }

            // [P2] NIK Ayah & Ibu
            if (!Schema::hasColumn('penduduk', 'nik_ayah')) {
                $table->string('nik_ayah', 16)->nullable()->comment('NIK Ayah — padanan ayah_nik OpenSID');
                $table->index('nik_ayah');
            }
            if (!Schema::hasColumn('penduduk', 'nik_ibu')) {
                $table->string('nik_ibu', 16)->nullable()->comment('NIK Ibu — padanan ibu_nik OpenSID');
                $table->index('nik_ibu');
            }

            // [P2] Kolom FK referensi
            if (!Schema::hasColumn('penduduk', 'agama_id')) {
                $table->foreignId('agama_id')->nullable()->constrained('ref_agama')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'pendidikan_kk_id')) {
                $table->unsignedBigInteger('pendidikan_kk_id')->nullable()->comment('FK ke ref_pendidikan');
                $table->foreign('pendidikan_kk_id')->references('id')->on('ref_pendidikan')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'pendidikan_sedang_id')) {
                $table->unsignedBigInteger('pendidikan_sedang_id')->nullable()->comment('FK ke ref_pendidikan');
                $table->foreign('pendidikan_sedang_id')->references('id')->on('ref_pendidikan')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'pekerjaan_id')) {
                $table->foreignId('pekerjaan_id')->nullable()->constrained('ref_pekerjaan')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'status_kawin_id')) {
                $table->foreignId('status_kawin_id')->nullable()->constrained('ref_status_kawin')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'golongan_darah_id')) {
                $table->foreignId('golongan_darah_id')->nullable()->constrained('ref_golongan_darah')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'warganegara_id')) {
                $table->foreignId('warganegara_id')->nullable()->constrained('ref_warganegara')->nullOnDelete();
            }

            // [P2] Data KTP Elektronik
            if (!Schema::hasColumn('penduduk', 'ktp_el')) $table->tinyInteger('ktp_el')->nullable();
            if (!Schema::hasColumn('penduduk', 'status_rekam')) $table->tinyInteger('status_rekam')->nullable();
            if (!Schema::hasColumn('penduduk', 'tempat_cetak_ktp')) $table->string('tempat_cetak_ktp', 100)->nullable();
            if (!Schema::hasColumn('penduduk', 'tanggal_cetak_ktp')) $table->date('tanggal_cetak_ktp')->nullable();

            // [P2] Detail Perkawinan
            if (!Schema::hasColumn('penduduk', 'akta_perkawinan')) $table->string('akta_perkawinan', 100)->nullable();
            if (!Schema::hasColumn('penduduk', 'tanggal_perkawinan')) $table->date('tanggal_perkawinan')->nullable();
            if (!Schema::hasColumn('penduduk', 'akta_perceraian')) $table->string('akta_perceraian', 100)->nullable();
            if (!Schema::hasColumn('penduduk', 'tanggal_perceraian')) $table->date('tanggal_perceraian')->nullable();

            // [P3] Data Kelahiran Detail
            if (!Schema::hasColumn('penduduk', 'waktu_lahir')) $table->time('waktu_lahir')->nullable();
            if (!Schema::hasColumn('penduduk', 'tempat_dilahirkan')) $table->string('tempat_dilahirkan', 100)->nullable();
            if (!Schema::hasColumn('penduduk', 'jenis_kelahiran')) $table->string('jenis_kelahiran', 50)->nullable();
            if (!Schema::hasColumn('penduduk', 'kelahiran_anak_ke')) $table->tinyInteger('kelahiran_anak_ke')->nullable();
            if (!Schema::hasColumn('penduduk', 'penolong_kelahiran')) $table->string('penolong_kelahiran', 50)->nullable();
            if (!Schema::hasColumn('penduduk', 'berat_lahir')) $table->decimal('berat_lahir', 5, 2)->nullable();
            if (!Schema::hasColumn('penduduk', 'panjang_lahir')) $table->decimal('panjang_lahir', 5, 2)->nullable();
            if (!Schema::hasColumn('penduduk', 'akta_lahir')) $table->string('akta_lahir', 100)->nullable();

            // [P3] Kesehatan
            if (!Schema::hasColumn('penduduk', 'cacat_id')) {
                $table->foreignId('cacat_id')->nullable()->constrained('ref_cacat')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'sakit_menahun_id')) {
                $table->foreignId('sakit_menahun_id')->nullable()->constrained('ref_sakit_menahun')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'cara_kb_id')) {
                $table->foreignId('cara_kb_id')->nullable()->constrained('ref_cara_kb')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'hamil')) {
                $table->tinyInteger('hamil')->default(0)->comment('0=Tidak, 1=Hamil');
            }

            // [P3] Asuransi
            if (!Schema::hasColumn('penduduk', 'asuransi_id')) {
                $table->foreignId('asuransi_id')->nullable()->constrained('ref_asuransi')->nullOnDelete();
            }
            if (!Schema::hasColumn('penduduk', 'no_asuransi')) $table->string('no_asuransi', 100)->nullable();

            // [P3] Dokumen Keimigrasian & Bahasa
            if (!Schema::hasColumn('penduduk', 'dokumen_pasport')) $table->string('dokumen_pasport', 100)->nullable();
            if (!Schema::hasColumn('penduduk', 'tanggal_akhir_paspor')) $table->date('tanggal_akhir_paspor')->nullable();
            if (!Schema::hasColumn('penduduk', 'dokumen_kitas')) $table->string('dokumen_kitas', 100)->nullable();
            if (!Schema::hasColumn('penduduk', 'negara_asal')) $table->string('negara_asal', 100)->nullable();
            if (!Schema::hasColumn('penduduk', 'bahasa_id')) {
                $table->foreignId('bahasa_id')->nullable()->constrained('ref_bahasa')->nullOnDelete();
            }

            // [P3] Alamat & catatan
            if (!Schema::hasColumn('penduduk', 'alamat_sebelumnya')) $table->text('alamat_sebelumnya')->nullable();
            if (!Schema::hasColumn('penduduk', 'no_kk_sebelumnya')) $table->string('no_kk_sebelumnya', 16)->nullable();
            if (!Schema::hasColumn('penduduk', 'keterangan')) $table->text('keterangan')->nullable();
        });

        // =====================================================================
        // STEP 3 — Fix jenis_tambah 
        // =====================================================================
        if (Schema::hasColumn('penduduk', 'jenis_tambah')) {
            DB::statement("UPDATE penduduk SET jenis_tambah = 'masuk' WHERE jenis_tambah = 'meninggal'");
            DB::statement("ALTER TABLE penduduk MODIFY COLUMN jenis_tambah ENUM('lahir','masuk') NOT NULL DEFAULT 'lahir'");
        }

        // =====================================================================
        // STEP 4 — Rename kolom varchar lama → _lama (Aman)
        // =====================================================================
        Schema::table('penduduk', function (Blueprint $table) {
            if (Schema::hasColumn('penduduk', 'agama') && !Schema::hasColumn('penduduk', 'agama_lama')) $table->renameColumn('agama', 'agama_lama');
            if (Schema::hasColumn('penduduk', 'pendidikan') && !Schema::hasColumn('penduduk', 'pendidikan_lama')) $table->renameColumn('pendidikan', 'pendidikan_lama');
            if (Schema::hasColumn('penduduk', 'pekerjaan') && !Schema::hasColumn('penduduk', 'pekerjaan_lama')) $table->renameColumn('pekerjaan', 'pekerjaan_lama');
            if (Schema::hasColumn('penduduk', 'status_kawin') && !Schema::hasColumn('penduduk', 'status_kawin_lama')) $table->renameColumn('status_kawin', 'status_kawin_lama');
            if (Schema::hasColumn('penduduk', 'golongan_darah') && !Schema::hasColumn('penduduk', 'golongan_darah_lama')) $table->renameColumn('golongan_darah', 'golongan_darah_lama');
            if (Schema::hasColumn('penduduk', 'kewarganegaraan') && !Schema::hasColumn('penduduk', 'kewarganegaraan_lama')) $table->renameColumn('kewarganegaraan', 'kewarganegaraan_lama');
        });
    }

    public function down(): void {
        // Abaikan down method untuk keamanan
    }
};