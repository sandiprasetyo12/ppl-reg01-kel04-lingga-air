<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Penyaluran Air Bersih</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #101828;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #D0D5DD;
            padding: 8px 10px;
            vertical-align: middle;
        }
        th {
            background-color: #00629B;
            color: #FFFFFF;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .header-title {
            font-size: 14pt;
            font-weight: bold;
            color: #00629B;
            text-align: center;
        }
        .header-sub {
            font-size: 11pt;
            text-align: center;
            color: #344054;
            margin-bottom: 15px;
        }
        .meta-table td {
            border: none;
            padding: 3px 5px;
            font-size: 10pt;
        }
        .status-terkirim {
            background-color: #ECFDF3;
            color: #027A48;
            font-weight: bold;
        }
        .status-pengiriman {
            background-color: #EBF5FB;
            color: #00629B;
            font-weight: bold;
        }
        .status-dijadwalkan {
            background-color: #FFFAEB;
            color: #B54708;
            font-weight: bold;
        }
        .status-kendala {
            background-color: #FEF3F2;
            color: #B42318;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td colspan="15" class="header-title">
                LAPORAN REKAPITULASI PENYALURAN AIR BERSIH DARURAT KEKERINGAN
            </td>
        </tr>
        <tr>
            <td colspan="15" class="header-sub">
                @if($isPenyalur && $penyalurAktif)
                    Mitra Penyalur: {{ $penyalurAktif->nama_penyalur }} | Kabupaten Purbalingga
                @else
                    Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Purbalingga
                @endif
            </td>
        </tr>
        <tr><td colspan="15" style="border:none;">&nbsp;</td></tr>
    </table>

    <!-- Metadata Laporan -->
    <table class="meta-table" style="margin-bottom: 15px;">
        <tr>
            <td style="width: 140px; font-weight: bold;">Tanggal Unduh</td>
            <td>: {{ $tanggalCetak }}</td>
            <td style="width: 140px; font-weight: bold;">Wilayah Filter</td>
            <td>: {{ !empty($filters['kecamatan']) ? 'Kec. ' . $filters['kecamatan'] : 'Semua Wilayah' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Periode Data</td>
            <td>: {{ !empty($filters['start_date']) ? $filters['start_date'] : 'Awal' }} s/d {{ !empty($filters['end_date']) ? $filters['end_date'] : 'Sekarang' }}</td>
            <td style="font-weight: bold;">Status Filter</td>
            <td>: {{ !empty($filters['status']) ? $filters['status'] : 'Semua Status' }}</td>
        </tr>
        @if(!$isPenyalur)
        <tr>
            <td style="font-weight: bold;">Mitra Armada</td>
            <td colspan="3">: {{ $penyalurAktif ? $penyalurAktif->nama_penyalur : 'Semua Mitra Penyalur' }}</td>
        </tr>
        @endif
    </table>

    <!-- Tabel Data Rekapitulasi -->
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Kode Penyaluran</th>
                <th>Nomor Tiket</th>
                <th>Tanggal</th>
                <th>Kecamatan</th>
                <th>Desa</th>
                <th>Titik Serah Terima</th>
                <th>Mitra Penyalur</th>
                <th>No Polisi</th>
                <th>Driver</th>
                <th>Volume (Liter)</th>
                <th>Jumlah Tangki</th>
                <th>Status</th>
                <th>Nama Penerima</th>
                <th>No. Telepon</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="mso-number-format:'\@';">{{ $row->kode_penyaluran }}</td>
                    <td style="mso-number-format:'\@';">{{ $row->nomor_tiket ?? '-' }}</td>
                    <td class="text-center">{{ $row->tanggal_penyaluran ? \Carbon\Carbon::parse($row->tanggal_penyaluran)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $row->desa->kecamatan ?? '-' }}</td>
                    <td>{{ $row->desa->nama_desa ?? $row->kode_desa }}</td>
                    <td>{{ $row->titik_serah_terima ?? '-' }}</td>
                    <td>{{ $row->penyalur->nama_penyalur ?? 'BPBD Purbalingga' }}</td>
                    <td style="mso-number-format:'\@';">{{ $row->armada_nopol ?? '-' }}</td>
                    <td>{{ $row->nama_driver ?? '-' }}</td>
                    <td class="text-right" style="mso-number-format:'\#\,\#\#0';">{{ $row->volume_air_liter }}</td>
                    <td class="text-center">{{ $row->jumlah_tangki }}</td>
                    <td class="text-center {{ $row->status === 'Terkirim' ? 'status-terkirim' : ($row->status === 'Dalam Pengiriman' ? 'status-pengiriman' : ($row->status === 'Dijadwalkan' ? 'status-dijadwalkan' : 'status-kendala')) }}">
                        {{ $row->status }}
                    </td>
                    <td>{{ $row->nama_penerima ?? '-' }}</td>
                    <td style="mso-number-format:'\@';">{{ $row->no_telepon_penerima ?? '-' }}</td>
                    <td>{{ $row->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center" style="padding: 20px; color: #667085;">
                        Tidak ada catatan penyaluran air bersih yang sesuai dengan filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($data->isNotEmpty())
            <tfoot>
                <tr style="background-color: #F8FAFC; font-weight: bold;">
                    <td colspan="10" class="text-right">TOTAL AIR BERSIH SUKSES TERKIRIM:</td>
                    <td class="text-right" style="color: #00629B; mso-number-format:'\#\,\#\#0';">{{ $totalVolume }} Liter</td>
                    <td class="text-center">{{ $totalTangki }} Tangki</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        @endif
    </table>

</body>
</html>
