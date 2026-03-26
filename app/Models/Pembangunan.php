<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Pembangunan - untuk tabel pembangunan (Struktur OpenSID)
 *
 * Kolom sesuai tabel:
 * id, config_id, id_bidang, id_sasaran, id_sumber_dana, id_lokasi
 * tahun_anggaran (year), nama, pelaksana, volume, satuan, waktu
 * mulai_pelaksanaan, akhir_pelaksanaan
 * dana_pemerintah, dana_provinsi, dana_kabkota, swadaya, sumber_lain (decimal 15,2)
 * lat (decimal 10,8), lng (decimal 11,8), foto, dokumentasi
 * status (tinyint: 1=aktif, 0=non-aktif)
 * created_at, updated_at
 */
class Pembangunan extends Model {
    protected $table = 'pembangunan';

    protected $fillable = [
        'config_id',
        'id_bidang',
        'id_sasaran',
        'id_sumber_dana',
        'id_lokasi',
        'tahun_anggaran',
        'nama',
        'pelaksana',
        'volume',
        'satuan',
        'waktu',
        'satuan_waktu',      // ← BARU
        'mulai_pelaksanaan',
        'akhir_pelaksanaan',
        'dana_pemerintah',
        'dana_provinsi',
        'dana_kabkota',
        'swadaya',
        'sumber_lain',
        'realisasi',         // ← BARU
        'sifat_proyek',      // ← BARU
        'lat',
        'lng',
        'foto',
        'dokumentasi',
        'manfaat',           // ← BARU
        'keterangan',        // ← BARU
        'status',
    ];

    protected $casts = [
        'tahun_anggaran'    => 'integer',
        'mulai_pelaksanaan' => 'date',
        'akhir_pelaksanaan' => 'date',
        // ── FIX #8: Sesuaikan presisi dengan DB decimal(15,2) ──
        'dana_pemerintah'   => 'decimal:2',
        'dana_provinsi'     => 'decimal:2',
        'dana_kabkota'      => 'decimal:2',
        'swadaya'           => 'decimal:2',
        'sumber_lain'       => 'decimal:2',
        'realisasi'         => 'decimal:2',  // ← BARU
        // ── FIX #8: Sesuaikan presisi dengan DB decimal(10,8) / decimal(11,8) ──
        'lat'               => 'decimal:8',
        'lng'               => 'decimal:8',
        // ── DB: tinyint — gunakan integer (1=aktif, 0=non-aktif) ──
        'status'            => 'integer',
        'volume'            => 'float',
    ];

    // ──────────────────────────────────────────────────────────
    // Relations
    // ──────────────────────────────────────────────────────────

    /**
     * Relasi ke dokumentasi / persentase pembangunan (1:N)
     * Di-order tanggal DESC → .first() = dokumentasi/progress TERBARU
     */
    public function dokumentasis(): HasMany {
        return $this->hasMany(PembangunanRefDokumentasi::class, 'id_pembangunan')
            ->orderByDesc('tanggal')
            ->orderByDesc('id');
    }

    /**
     * Relasi ke bidang pembangunan
     */
    public function bidang(): BelongsTo {
        return $this->belongsTo(RefPembangunanBidang::class, 'id_bidang');
    }

    /**
     * Relasi ke sasaran pembangunan
     */
    public function sasaran(): BelongsTo {
        return $this->belongsTo(RefPembangunanSasaran::class, 'id_sasaran');
    }

    /**
     * Relasi ke sumber dana
     */
    public function sumberDana(): BelongsTo {
        return $this->belongsTo(RefPembangunanSumberDana::class, 'id_sumber_dana');
    }

    /**
     * FIX #2: Relasi ke wilayah/lokasi administratif (dusun/RW/RT)
     * Sesuai OpenSID: id_lokasi → tweb_wil_clusterdesa (di sini: tabel wilayah)
     *
     * Pastikan Model Wilayah sudah ada di App\Models\Wilayah
     * dan mengarah ke tabel 'wilayah'
     */
    public function lokasi(): BelongsTo {
        return $this->belongsTo(\App\Models\Wilayah::class, 'id_lokasi');
    }

    // ──────────────────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────────────────

    /** Scope: data aktif saja (status = 1) */
    public function scopeAktif($query) {
        return $query->where('status', 1);
    }

    /** Scope: filter berdasarkan tahun anggaran */
    public function scopeTahun($query, $tahun) {
        return $query->where('tahun_anggaran', $tahun);
    }

    // ──────────────────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────────────────

    /**
     * Pagu Anggaran = total semua sumber dana
     * Alias: total_anggaran (kompatibel dengan kode lama)
     */
    public function getTotalAnggaranAttribute(): float {
        return (float) $this->dana_pemerintah
            + (float) $this->dana_provinsi
            + (float) $this->dana_kabkota
            + (float) $this->swadaya
            + (float) $this->sumber_lain;
    }

    /**
     * FIX #3: Progres terkini = dokumentasi PERTAMA (relasi sudah order DESC)
     *
     * Bug lama di view pakai ->last() yang justru mengambil yang paling LAMA.
     * Accessor ini sudah benar: ->first() = tanggal terbaru = progres terbaru.
     */
    public function getPersentaseTerkiniAttribute(): int {
        if ($this->relationLoaded('dokumentasis') && $this->dokumentasis->isNotEmpty()) {
            return (int) $this->dokumentasis->first()->persentase;
        }
        return 0;
    }

    /**
     * Label status untuk tampilan
     */
    public function getStatusLabelAttribute(): string {
        return $this->status == 1 ? 'Aktif' : 'Non-Aktif';
    }

    /**
     * Foto URL accessor
     */
    public function getFotoUrlAttribute(): ?string {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    /**
     * Apakah pembangunan sudah selesai (progres = 100)?
     */
    public function getIsSelesaiAttribute(): bool {
        return $this->persentase_terkini >= 100;
    }
}
