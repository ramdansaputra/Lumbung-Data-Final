<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesan';
    protected $guarded = ['id'];
    protected $casts = ['waktu_dibaca' => 'datetime'];

    // Relasi Pengirim
    public function pengirim() {
        return $this->belongsTo(Users::class, 'pengirim_id');
    }

    // Relasi Penerima
    public function penerima() {
        return $this->belongsTo(Users::class, 'penerima_id');
    }

    // Threading (Balasan)
    public function balasan() {
        return $this->hasMany(Pesan::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}
