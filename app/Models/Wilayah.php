<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model {
    protected $table = 'wilayah';

    protected $fillable = [
        'desa_id',
        'dusun',
        'rw',
        'rt',
        'ketua_rt',
        'ketua_rw',
        'jumlah_kk',
        'jumlah_penduduk',
        'laki_laki',
        'perempuan',
    ];

    public function penduduk() {
        return $this->hasMany(Penduduk::class);
    }

    public function keluarga() {
        return $this->hasMany(Keluarga::class);
    }

    public function desa() {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Label lokasi gabungan dari dusun, RW, dan RT.
     * Contoh hasil: "Dsn. Karang Anyar / RW 02 / RT 05"
     * Dipakai di blade dengan: $p->lokasi->label
     */
    public function getLabelAttribute(): string {
        $parts = array_filter([
            $this->dusun ? 'Dsn. ' . $this->dusun : null,
            $this->rw    ? 'RW '   . $this->rw    : null,
            $this->rt    ? 'RT '   . $this->rt    : null,
        ]);
        return implode(' / ', $parts) ?: '-';
    }
}
