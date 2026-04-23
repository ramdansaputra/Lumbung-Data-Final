<?php

return [
    'nama_desa'    => env('DESA_NAMA',         'Nama Desa Anda'),
    'sebutan_desa' => env('DESA_SEBUTAN',       'Desa'),   // atau 'Kelurahan'
    'kecamatan'    => env('DESA_KECAMATAN',     'Nama Kecamatan'),
    'kabupaten'    => env('DESA_KABUPATEN',     'Nama Kabupaten'),
    'provinsi'     => env('DESA_PROVINSI',      'Nama Provinsi'),
    'kode_pos'     => env('DESA_KODE_POS',      '00000'),
    'kode_desa'    => env('DESA_KODE',          '0000000000'), // 10 digit, untuk NIK sementara
    'nama_kades'   => env('DESA_NAMA_KADES',    'Nama Kepala Desa'),
    'jabatan_kades' => env('DESA_JABATAN_KADES', 'Kepala Desa'),
];
