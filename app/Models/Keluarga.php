<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keluarga extends Model {
    use SoftDeletes;

    protected $table = 'keluarga';

    protected $fillable = [
        'no_kk',
        'nik_kepala',
        'kepala_keluarga_id',
        'rumah_tangga_id',
        'alamat',
        'wilayah_id',
        'tgl_terdaftar',
        'tgl_cetak_kk',
        'status',              // 1=Aktif, 0=Tidak Aktif
        'klasifikasi_ekonomi', // dipertahankan — belum dipindah ke tabel RTM
        'jenis_bantuan_aktif', // dipertahankan — belum dipindah ke tabel bantuan
    ];

    protected $casts = [
        'tgl_terdaftar' => 'date',
        'tgl_cetak_kk'  => 'date',
        'status'        => 'boolean',
    ];

    // =========================================================================
    // KONSTANTA
    // =========================================================================

    public const STATUS_AKTIF       = 1;
    public const STATUS_TIDAK_AKTIF = 0;

    // =========================================================================
    // RELASI
    // =========================================================================

    /**
     * Semua anggota keluarga — via FK penduduk.keluarga_id.
     */
    public function anggota() {
        return $this->hasMany(Penduduk::class, 'keluarga_id');
    }

    /**
     * Kepala Keluarga — via FK kepala_keluarga_id.
     * Lebih efisien daripada query WHERE kk_level = 1.
     */
    public function kepalaKeluarga() {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_id');
    }

    /**
     * Rumah Tangga yang menaungi KK ini.
     * Ini adalah jalur resmi akses RTM dari Penduduk:
     *   Penduduk → keluarga() → rumahTangga()
     */
    public function rumahTangga() {
        return $this->belongsTo(RumahTangga::class, 'rumah_tangga_id');
    }

    /**
     * Wilayah (RT/RW/Dusun).
     */
    public function wilayah() {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Ambil kepala keluarga.
     * Prioritas: pakai relasi kepalaKeluarga() via FK (lebih cepat).
     * Fallback: query ke anggota dengan kk_level = 1.
     */
    public function getKepalaKeluarga(): ?Penduduk {
        if ($this->kepala_keluarga_id) {
            return $this->relationLoaded('kepalaKeluarga')
                ? $this->kepalaKeluarga
                : $this->kepalaKeluarga()->first();
        }

        // Fallback via kk_level
        return $this->anggota()->where('kk_level', Penduduk::SHDK_KEPALA_KELUARGA)->first();
    }

    /**
     * Ambil semua anggota KECUALI kepala keluarga.
     */
    public function getAnggotaNonKepala() {
        return $this->anggota()->where('kk_level', '!=', Penduduk::SHDK_KEPALA_KELUARGA);
    }

    /**
     * Cek apakah sudah punya kepala keluarga.
     */
    public function hasKepalaKeluarga(): bool {
        if ($this->kepala_keluarga_id) return true;
        return $this->anggota()->where('kk_level', Penduduk::SHDK_KEPALA_KELUARGA)->exists();
    }

    /**
     * Total anggota keluarga (hitung dinamis, tanpa cache di kolom).
     */
    public function getTotalAnggota(): int {
        return $this->relationLoaded('anggota')
            ? $this->anggota->count()
            : $this->anggota()->count();
    }

    /**
     * Jumlah anggota laki-laki.
     */
    public function getTotalLakiLaki(): int {
        return $this->anggota()->where('jenis_kelamin', 'L')->count();
    }

    /**
     * Jumlah anggota perempuan.
     */
    public function getTotalPerempuan(): int {
        return $this->anggota()->where('jenis_kelamin', 'P')->count();
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeAktif($query) {
        return $query->where('status', self::STATUS_AKTIF);
    }

    public function scopeTidakAktif($query) {
        return $query->where('status', self::STATUS_TIDAK_AKTIF);
    }

    // =========================================================================
    // BOOT — validasi & event
    // =========================================================================

    protected static function boot() {
        parent::boot();

        /**
         * Cegah hapus KK yang masih punya anggota aktif.
         * Soft delete tetap diizinkan — ini hanya untuk forceDelete.
         */
        static::forceDeleting(function ($keluarga) {
            $jumlah = $keluarga->anggota()->count();
            if ($jumlah > 0) {
                throw new \Exception("Keluarga {$keluarga->no_kk} tidak bisa dihapus permanen karena masih memiliki {$jumlah} anggota.");
            }
        });
    }
}
