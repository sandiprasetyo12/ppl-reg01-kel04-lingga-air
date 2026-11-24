<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Resmi Penyaluran Air Bersih - BPBD Purbalingga</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background-color: #F8FAFC;
            color: #101828;
            font-size: 11px;
            line-height: 1.4;
            padding: 24px;
        }
        .container {
            max-width: 1080px;
            margin: 0 auto;
            background: #ffffff;
            padding: 32px 40px;
            border-radius: 12px;
            border: 1px solid #E4E7EC;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05);
        }
        
        /* Toolbar Aksi Atas (Hanya tampil di browser) */
        .action-toolbar {
            max-width: 1080px;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            border: 1px solid #E4E7EC;
        }
        .btn-print {
            background-color: #00629B;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
        }
        .btn-print:hover {
            background-color: #004F7D;
        }
        .btn-close {
            background-color: #F8FAFC;
            color: #344054;
            border: 1px solid #D0D5DD;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        /* Kop Surat Resmi */
        .kop-surat {
            border-bottom: 3px double #101828;
            padding-bottom: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .kop-logo {
            width: 75px;
            height: auto;
            object-fit: contain;
        }
        .kop-text {
            flex: 1;
            text-align: center;
        }
        .kop-text h2 {
            font-size: 14px;
            font-weight: 700;
            color: #101828;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-text h1 {
            font-size: 16px;
            font-weight: 800;
            color: #00629B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 2px 0;
        }
        .kop-text p {
            font-size: 10px;
            color: #475467;
        }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            margin-bottom: 18px;
        }
        .doc-title h3 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: #101828;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 11px;
            color: #667085;
            margin-top: 2px;
        }

        /* Parameter Filter Aktif */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 16px;
            background: #F8FAFC;
            border: 1px solid #EAECF0;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 10.5px;
        }
        .meta-item {
            display: flex;
            gap: 6px;
        }
        .meta-label {
            font-weight: 600;
            color: #475467;
            min-width: 110px;
        }
        .meta-val {
            color: #101828;
            font-weight: 500;
        }

        /* Tabel Data Resmi */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 18px;
        }
        table.report-table th, table.report-table td {
            border: 1px solid #D0D5DD;
            padding: 6px 8px;
            vertical-align: middle;
        }
        table.report-table th {
            background-color: #EBF5FB;
            color: #00629B;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.3px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-align: center;
        }
        .badge-sukses { background: #ECFDF3; color: #027A48; border: 1px solid #A6F4C5; }
        .badge-proses { background: #EBF5FB; color: #00629B; border: 1px solid #BAE3F5; }
        .badge-jadwal { background: #FFFAEB; color: #B54708; border: 1px solid #FEDF89; }
        .badge-kendala { background: #FEF3F2; color: #B42318; border: 1px solid #FECDCA; }

        /* Tanda Tangan & Pengesahan */
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box {
            width: 250px;
            text-align: center;
            font-size: 11px;
        }
        .signature-box .signature-space {
            height: 65px;
        }

        /* Cetak / Print Styles */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .action-toolbar {
                display: none !important;
            }
            .container {
                border: none;
                box-shadow: none;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }
            table.report-table th {
                background-color: #EBF5FB !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Toolbar Aksi Layar Browser -->
    <div class="action-toolbar">
        <div>
            <span style="font-weight: 700; color: #101828;">Pratinjau Cetak Dokumen Resmi</span>
            <span style="color: #667085; font-size: 11px; margin-left: 8px;">Format Siap Cetak (A4 Landscape / Potret)</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="btn-close">
                <i class="fa-solid fa-xmark"></i> Tutup
            </button>
        </div>
    </div>

    <!-- Lembar Dokumen Resmi -->
    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo-lingga.png') }}" alt="Logo Lingga Air" class="kop-logo" onerror="this.style.display='none'">
            <div class="kop-text">
                <h2>Pemerintah Kabupaten Purbalingga</h2>
                <h1>Badan Penanggulangan Bencana Daerah (BPBD)</h1>
                <p>Jl. Mayjen Sungkono No. 37 Purbalingga, Jawa Tengah 53311 | Telp: (0281) 891011 | Email: bpbd@purbalinggakab.go.id</p>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="doc-title">
            <h3>Laporan Rekapitulasi Penyaluran Bantuan Air Bersih Darurat</h3>
            <p>Sistem Informasi Penyaluran Air Bersih Tanggap Darurat Kekeringan (Lingga Air)</p>
        </div>

        <!-- Parameter Metadata Filter -->
        <div class="meta-grid">
            <div class="meta-item">
                <span class="meta-label">Periode Tanggal</span>
                <span class="meta-val">: {{ !empty($filters['start_date']) ? \Carbon\Carbon::parse($filters['start_date'])->translatedFormat('d M Y') : 'Semua Tanggal' }} s/d {{ !empty($filters['end_date']) ? \Carbon\Carbon::parse($filters['end_date'])->translatedFormat('d M Y') : 'Sekarang' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Wilayah Kecamatan</span>
                <span class="meta-val">: {{ !empty($filters['kecamatan']) ? 'Kec. ' . $filters['kecamatan'] : 'Seluruh Kecamatan' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Mitra Penyalur</span>
                <span class="meta-val">: {{ $penyalurAktif ? $penyalurAktif->nama_penyalur : ($isPenyalur ? 'Armada Penyalur' : 'Seluruh Mitra Penyalur (PMI/PDAM/BPBD)') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Status Distribusi</span>
                <span class="meta-val">: {{ !empty($filters['status']) ? $filters['status'] : 'Seluruh Status Penyaluran' }}</span>
            </div>
        </div>

        <!-- Tabel Data -->
        <table class="report-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 25px;">No</th>
                    <th style="width: 85px;">Kode Penyaluran</th>
                    <th style="width: 70px;">Tanggal</th>
                    <th>Desa & Kecamatan</th>
                    <th>Titik Serah Terima</th>
                    <th>Mitra & No Polisi</th>
                    <th class="text-right" style="width: 60px;">Volume</th>
                    <th class="text-center" style="width: 45px;">Tangki</th>
                    <th>Penerima</th>
                    <th class="text-center" style="width: 70px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-mono font-bold" style="color: #00629B;">{{ $row->kode_penyaluran }}</td>
                        <td>{{ $row->tanggal_penyaluran ? \Carbon\Carbon::parse($row->tanggal_penyaluran)->translatedFormat('d/m/Y') : '-' }}</td>
                        <td>
                            <strong>Desa {{ $row->desa->nama_desa ?? $row->kode_desa }}</strong><br>
                            <span style="color: #667085; font-size: 9px;">Kec. {{ $row->desa->kecamatan ?? '-' }}</span>
                        </td>
                        <td>{{ $row->titik_serah_terima ?? '-' }}</td>
                        <td>
                            {{ $row->penyalur->nama_penyalur ?? 'BPBD' }}<br>
                            <span class="font-mono" style="color: #667085; font-size: 9px;">{{ $row->armada_nopol ?? '-' }}</span>
                        </td>
                        <td class="text-right font-bold">{{ number_format($row->volume_air_liter, 0, ',', '.') }} L</td>
                        <td class="text-center">{{ $row->jumlah_tangki }}</td>
                        <td>
                            {{ $row->nama_penerima ?? '-' }}
                            @if($row->no_telepon_penerima)
                                <br><span style="color: #667085; font-size: 9px;">{{ $row->no_telepon_penerima }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row->status === 'Terkirim')
                                <span class="badge badge-sukses">Terkirim</span>
                            @elseif($row->status === 'Dalam Pengiriman')
                                <span class="badge badge-proses">Pengiriman</span>
                            @elseif($row->status === 'Dijadwalkan')
                                <span class="badge badge-jadwal">Dijadwalkan</span>
                            @else
                                <span class="badge badge-kendala">Kendala</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 24px; color: #667085;">
                            Tidak ada catatan penyaluran air bersih yang sesuai dengan filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($data->isNotEmpty())
                <tfoot>
                    <tr style="background-color: #F8FAFC; font-weight: bold;">
                        <td colspan="6" class="text-right">TOTAL AIR BERSIH SUKSES TERKIRIM:</td>
                        <td class="text-right" style="color: #00629B;">{{ number_format($totalVolume, 0, ',', '.') }} L</td>
                        <td class="text-center">{{ $totalTangki }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- Lembar Tanda Tangan & Pengesahan -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Purbalingga, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="font-weight: 600; margin-top: 4px;">
                    @if($isPenyalur && $penyalurAktif)
                        Koordinator Armada {{ $penyalurAktif->nama_penyalur }}
                    @else
                        a.n. Kepala Pelaksana BPBD Kab. Purbalingga<br>
                        Kabid Kedaruratan & Logistik
                    @endif
                </p>
                <div class="signature-space"></div>
                <p style="font-weight: 700; text-decoration: underline;">
                    @if($isPenyalur && $penyalurAktif)
                        ( {{ $user->username ?? 'Petugas Armada' }} )
                    @else
                        ( Raditya Wibowo, S.STP, M.Si )
                    @endif
                </p>
                <p style="color: #667085; font-size: 9.5px;">
                    @if($isPenyalur)
                        Petugas Operasional Terdaftar
                    @else
                        NIP. 19820412 200604 1 008
                    @endif
                </p>
            </div>
        </div>

    </div>

</body>
</html>
