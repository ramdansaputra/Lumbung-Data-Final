<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggaranTahunan extends Model
{
    use HasFactory;

    protected $table = 'anggaran_tahunans';

    protected $fillable = [
        'akun_rekening_id',
        'tahun',
        'anggaran',
        'realisasi'
    ];

    // Relasi: 1 Anggaran Tahunan MILIK 1 Akun Rekening (belongsTo)
    public function akunRekening()
    {
        return $this->belongsTo(AkunRekening::class, 'akun_rekening_id');
    }
}