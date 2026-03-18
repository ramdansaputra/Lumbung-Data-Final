<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Rencana Kerja Pembangunan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
        }

        /* ── Wrapper halaman cetak ── */
        .page {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px 24px 30px;
        }

        /* ── Header surat ── */
        .header {
            text-align: center;
            margin-bottom: 16px;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .header h2 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            font-size: 10px;
            margin-top: 4px;
            color: #333;
        }

        /* ── Info filter ── */
        .filter-info {
            margin-bottom: 12px;
            font-size: 10px;
            color: #444;
        }

        .filter-info span {
            display: inline-block;
            margin-right: 16px;
        }

        .filter-info strong {
            color: #000;
        }

        /* ── Tabel ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        thead tr th {
            background-color: #d0d8e4;
            border: 1px solid #333;
            padding: 5px 6px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            vertical-align: middle;
        }

        tbody tr td {
            border: 1px solid #555;
            padding: 4px 6px;
            font-size: 10px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) td {
            background-color: #f7f7f7;
        }

        /* Kolom angka */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ── Baris total footer ── */
        tfoot tr td {
            border: 1px solid #333;
            padding: 5px 6px;
            font-size: 10px;
            font-weight: bold;
            background-color: #e8edf2;
        }

        /* ── Tanda tangan ── */
        .ttd-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .ttd-box {
            text-align: center;
            font-size: 10px;
            min-width: 200px;
        }

        .ttd-box .ttd-space {
            height: 60px;
        }

        .ttd-box .ttd-name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 3px;
        }

        /* ── Tombol aksi (tidak ikut cetak) ── */
        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            padding: 12px 16px;
            background: #f0f4f8;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-print {
            background: #1e40af;
            color: #fff;
        }

        .btn-back {
            background: #64748b;
            color: #fff;
        }

        .btn-print:hover { background: #1e3a8a; }
        .btn-back:hover  { background: #475569; }

        /* ── Sembunyikan action-bar saat cetak ── */
        @media print {
            .action-bar {
                display: none !important;
            }

            body {
                font-size: 10px;
            }

            .page {
                padding: 10px 14px 20px;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ── TOMBOL AKSI (tidak ikut cetak) ── --}}
    <div class="action-bar">
        <button class="btn btn-print" onclick="window.print()">
            🖨️ Cetak Halaman Ini
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-back">
            ← Kembali
        </a>
    </div>

    {{-- ── HEADER ── --}}
    <div class="header">
        <h1>Pemerintah Desa</h1>
        <h2>Buku Rencana Kerja Pembangunan</h2>
        <p>
            @if($filterTahun)
                Tahun Anggaran: <strong>{{ $filterTahun }}</strong>
            @else
                Semua Tahun Anggaran
            @endif
            @if($filterSearch)
                &nbsp;|&nbsp; Pencarian: <strong>{{ $filterSearch }}</strong>
            @endif
        </p>
    </div>

    {{-- ── INFO FILTER ── --}}
    <div class="filter-info">
        <span>Dicetak pada: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WIB</strong></span>
        <span>Total Kegiatan: <strong>{{ $pembangunan->count() }}</strong></span>
        <span>Total Anggaran: <strong>Rp {{ number_format($totalAnggaran ?? 0, 0, ',', '.') }}</strong></span>
    </div>

    {{-- ── TABEL ── --}}
    @if($pembangunan->isEmpty())
        <p style="text-align:center; padding: 30px; color:#666;">Tidak ada data untuk dicetak.</p>
    @else
    <table>
        <thead>
            {{-- Baris 1 --}}
            <tr>
                <th rowspan="2" style="width:28px;">No</th>
                <th rowspan="2" style="min-width:160px;">Nama Proyek / Kegiatan</th>
                <th rowspan="2" style="min-width:80px;">Lokasi</th>
                <th colspan="5">Sumber Dana</th>
                <th rowspan="2" style="min-width:90px;">Jumlah (Rp)</th>
                <th rowspan="2" style="min-width:80px;">Pelaksana</th>
                <th rowspan="2" style="min-width:90px;">Manfaat</th>
                <th rowspan="2" style="min-width:70px;">Ket.</th>
            </tr>
            {{-- Baris 2: sub-header sumber dana --}}
            <tr>
                <th style="min-width:80px;">Pemerintah</th>
                <th style="min-width:80px;">Provinsi</th>
                <th style="min-width:80px;">Kab/Kota</th>
                <th style="min-width:80px;">Swadaya</th>
                <th style="min-width:80px;">Sumber Lain</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembangunan as $i => $p)
            @php $jumlah = $p->total_anggaran; @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>

                {{-- Nama + tahun --}}
                <td>
                    <strong>{{ $p->nama }}</strong>
                    @if($p->tahun_anggaran)
                        <br><span style="color:#555;">({{ $p->tahun_anggaran }})</span>
                    @endif
                </td>

                {{-- Lokasi via relasi lokasi() --}}
                <td>{{ $p->lokasi->label ?? '-' }}</td>

                {{-- dana_pemerintah --}}
                <td class="text-right">
                    @if($p->dana_pemerintah > 0)
                        {{ number_format($p->dana_pemerintah, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>

                {{-- dana_provinsi --}}
                <td class="text-right">
                    @if($p->dana_provinsi > 0)
                        {{ number_format($p->dana_provinsi, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>

                {{-- dana_kabkota --}}
                <td class="text-right">
                    @if($p->dana_kabkota > 0)
                        {{ number_format($p->dana_kabkota, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>

                {{-- swadaya --}}
                <td class="text-right">
                    @if($p->swadaya > 0)
                        {{ number_format($p->swadaya, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>

                {{-- sumber_lain --}}
                <td class="text-right">
                    @if($p->sumber_lain > 0)
                        {{ number_format($p->sumber_lain, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>

                {{-- Jumlah (accessor total_anggaran) --}}
                <td class="text-right"><strong>{{ number_format($jumlah, 0, ',', '.') }}</strong></td>

                {{-- pelaksana --}}
                <td>{{ $p->pelaksana ?? '-' }}</td>

                {{-- manfaat --}}
                <td>{{ $p->manfaat ?? '-' }}</td>

                {{-- keterangan --}}
                <td>{{ $p->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>

        {{-- FOOTER TOTAL --}}
        <tfoot>
            <tr>
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($totalDanaPemerintah ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalDanaProvinsi   ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalDanaKabkota    ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalSwadaya        ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalSumberLain     ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalAnggaran       ?? 0, 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- ── TANDA TANGAN ── --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <p>
                @if($filterTahun)
                    ............., {{ $filterTahun }}
                @else
                    ............., {{ now()->year }}
                @endif
            </p>
            <p style="margin-top:4px;">Kepala Desa</p>
            <div class="ttd-space"></div>
            <p class="ttd-name">( .................................................. )</p>
        </div>
    </div>

</div>
</body>
</html>