<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PermohonanInformasi extends Model {
    protected $table = 'permohonan_informasi';

    protected $fillable = [
        'nomor_permohonan',
        'nik',
        'nama_pemohon',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'alamat',
        'no_telp',
        'email',
        'informasi_yang_dibutuhkan',
        'tujuan_penggunaan',
        'cara_memperoleh',
        'cara_mendapatkan_salinan',
        'status',
        'tindak_lanjut',
        'alasan_penolakan',
        'tanggal_permohonan',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_lahir'      => 'date',
        'tanggal_permohonan' => 'date',
        'tanggal_selesai'    => 'date',
    ];

    // Status label & badge color
    public function getStatusLabelAttribute(): string {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'proses'   => 'Proses',
            'selesai'  => 'Selesai',
            'ditolak'  => 'Ditolak',
            default    => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string {
        return match ($this->status) {
            'menunggu' => 'yellow',
            'proses'   => 'blue',
            'selesai'  => 'green',
            'ditolak'  => 'red',
            default    => 'gray',
        };
    }

    // Auto-generate nomor permohonan
    public static function generateNomor(): string {
        $tahun  = date('Y');
        $bulan  = date('m');
        $count  = static::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count() + 1;
        return sprintf('PI-%s%s-%04d', $tahun, $bulan, $count);
    }
}
