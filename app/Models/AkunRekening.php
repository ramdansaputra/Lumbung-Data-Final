<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunRekening extends Model
{
    use HasFactory;

    // Nama tabel secara eksplisit (opsional tapi disarankan)
    protected $table = 'akun_rekenings';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'kode_rekening',
        'uraian',
        'is_editable'
    ];

    // Relasi: 1 Akun Rekening punya BANYAK Anggaran Tahunan (hasMany)
    public function anggaranTahunans()
    {
        return $this->hasMany(AnggaranTahunan::class, 'akun_rekening_id');
    }
}