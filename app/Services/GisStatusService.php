<?php

namespace App\Services;

use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\Permohonan;
use App\Models\RiwayatCekKelayakan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class GisStatusService
{
    public function __construct(
        private readonly CekKelayakanService $cekKelayakanService,
    ) {
    }

    /**
     * Warna dan status GIS resmi untuk Model A (PBI-025).
     */
    public const STATUS_META = [
        'merah' => [
            'warna_key' => 'merah',
            'kode_warna' => '#DC2626',
            'status' => 'Perlu Penanganan',
            'label' => 'Pengajuan Menunggu Verifikasi / Telaah BPBD',
        ],
        'kuning' => [
            'warna_key' => 'kuning',
            'kode_warna' => '#D97706',
            'status' => 'Proses Penyaluran',
            'label' => 'Disetujui / Dijadwalkan Penyaluran',
        ],
        'hijau' => [
            'warna_key' => 'hijau',
            'kode_warna' => '#059669',
            'status' => 'Tersalurkan',
            'label' => 'Bantuan Air Bersih Telah Disalurkan',
        ],
        'abu_abu' => [
            'warna_key' => 'abu_abu',
            'kode_warna' => '#6B7280',
            'status' => 'Tanpa Tiket',
            'label' => 'Belum Ada Pengajuan Aktif',
        ],
    ];

    /**
     * Bangun seluruh payload peta (summary, markers, unmapped) secara efisien tanpa N+1 query.
     *
     * @return array{
     *   summary: array<string, int>,
     *   markers: array<int, array<string, mixed>>,
     *   unmapped: array<int, array<string, mixed>>
     * }
     */
    public function buildMapData(Collection $mappedDesas, Collection $unmappedDesas, ?string $statusFilter = null): array
    {
        $allKodeDesas = $mappedDesas->pluck('kode_desa')
            ->merge($unmappedDesas->pluck('kode_desa'))
            ->unique()
            ->values();

        $context = $this->preloadRelatedData($allKodeDesas);

        $summary = [
            'merah' => 0,
            'kuning' => 0,
            'hijau' => 0,
            'abu_abu' => 0,
            'total_terpetakan' => $mappedDesas->count(),
            'ditampilkan' => 0,
            'tanpa_koordinat' => $unmappedDesas->count(),
        ];

        $markers = [];
        foreach ($mappedDesas as $desa) {
            $marker = $this->formatMarkerItem($desa, $context);
            $warnaKey = $marker['status_gis']['warna_key'];
            $summary[$warnaKey] = ($summary[$warnaKey] ?? 0) + 1;

            if ($statusFilter && $warnaKey !== $statusFilter) {
                continue;
            }

            $markers[] = $marker;
        }

        $summary['ditampilkan'] = count($markers);

        $unmapped = [];
        foreach ($unmappedDesas as $desa) {
            $resolved = $this->resolveDesaContext($desa, $context);
            $statusGis = $this->getStatusWarna(
                $desa,
                $resolved['pengajuan'],
                $resolved['permohonan'],
                $resolved['permohonan_by_tiket']
            );

            $unmapped[] = [
                'kode_desa' => $desa->kode_desa,
                'nama_desa' => $desa->nama_desa,
                'kecamatan' => $desa->kecamatan,
                'titik_drop_air' => $desa->titik_drop_air,
                'status_gis' => $statusGis,
                'kelayakan' => $this->getKelayakanData(
                    $desa,
                    $resolved['riwayat'],
                    $resolved['permohonan']
                ),
            ];
        }

        return [
            'summary' => $summary,
            'markers' => $markers,
            'unmapped' => $unmapped,
        ];
    }

    /**
     * Hitung ringkasan status awal seluruh desa terpetakan untuk rendering blade/overview.
     *
     * @return array<string, int>
     */
    public function getInitialSummary(): array
    {
        $mappedDesas = Desa::siapPeta()->orderBy('kecamatan')->orderBy('nama_desa')->get();
        $unmappedCount = Desa::belumSiapPeta()->count();
        $context = $this->preloadRelatedData($mappedDesas->pluck('kode_desa'));

        $summary = [
            'merah' => 0,
            'kuning' => 0,
            'hijau' => 0,
            'abu_abu' => 0,
            'total_terpetakan' => $mappedDesas->count(),
            'ditampilkan' => $mappedDesas->count(),
            'tanpa_koordinat' => $unmappedCount,
        ];

        foreach ($mappedDesas as $desa) {
            $resolved = $this->resolveDesaContext($desa, $context);
            $statusGis = $this->getStatusWarna(
                $desa,
                $resolved['pengajuan'],
                $resolved['permohonan'],
                $resolved['permohonan_by_tiket']
            );
            $warnaKey = $statusGis['warna_key'];
            $summary[$warnaKey] = ($summary[$warnaKey] ?? 0) + 1;
        }

        return $summary;
    }

    /**
     * Ambil payload marker tunggal untuk desa (dipakai setelah update koordinat).
     *
     * @return array<string, mixed>
     */
    public function buildDesaMarkerSummary(Desa $desa): array
    {
        $context = $this->preloadRelatedData(collect([$desa->kode_desa]));

        return $this->formatMarkerItem($desa, $context);
    }

    /**
     * Ambil payload detail lengkap satu desa untuk panel PBI-026 (GET /admin/pemetaan/{kode_desa}).
     *
     * @return array<string, mixed>
     */
    public function buildDesaDetail(Desa $desa): array
    {
        $context = $this->preloadRelatedData(collect([$desa->kode_desa]));
        $resolved = $this->resolveDesaContext($desa, $context);

        $pengajuan = $resolved['pengajuan'];
        $permohonan = $resolved['permohonan'];
        $permohonanByTiket = $resolved['permohonan_by_tiket'];
        $riwayat = $resolved['riwayat'];

        $statusGis = $this->getStatusWarna($desa, $pengajuan, $permohonan, $permohonanByTiket);
        $kelayakan = $this->getKelayakanData($desa, $riwayat, $permohonan);
        $tiketData = $this->buildPengajuanDetail($desa, $pengajuan, $permohonan, $permohonanByTiket, $statusGis);

        return [
            'kode_desa' => $desa->kode_desa,
            'nama_desa' => $desa->nama_desa,
            'kecamatan' => $desa->kecamatan,
            'latitude' => $desa->latitude !== null ? (float) $desa->latitude : null,
            'longitude' => $desa->longitude !== null ? (float) $desa->longitude : null,
            'koordinat_siap' => $this->hasValidCoordinates($desa),
            'titik_drop_air' => $desa->titik_drop_air,
            'nama_pj_darurat' => $desa->nama_pj_darurat,
            'no_wa_darurat' => $desa->no_wa_darurat,
            'demografi' => [
                'jumlah_kk_total' => $desa->jumlah_kk_total !== null ? (int) $desa->jumlah_kk_total : null,
                'jumlah_jiwa_total' => $desa->jumlah_jiwa_total !== null ? (int) $desa->jumlah_jiwa_total : null,
                'jumlah_balita' => $desa->jumlah_balita !== null ? (int) $desa->jumlah_balita : null,
                'jumlah_lansia' => $desa->jumlah_lansia !== null ? (int) $desa->jumlah_lansia : null,
                'jumlah_disabilitas' => $desa->jumlah_disabilitas !== null ? (int) $desa->jumlah_disabilitas : null,
                'total_jiwa_rentan' => $desa->total_jiwa_rentan,
            ],
            'status_gis' => $statusGis,
            'kelayakan' => $kelayakan,
            'pengajuan_aktif' => $tiketData,
        ];
    }

    /**
     * Resolve status warna GIS untuk accessor $desa->status_gis.
     *
     * @return array{warna_key: string, kode_warna: string, status: string, label: string}
     */
    public function resolveStatusForDesa(Desa $desa): array
    {
        $context = $this->preloadRelatedData(collect([$desa->kode_desa]));
        $resolved = $this->resolveDesaContext($desa, $context);

        return $this->getStatusWarna(
            $desa,
            $resolved['pengajuan'],
            $resolved['permohonan'],
            $resolved['permohonan_by_tiket']
        );
    }

    /**
     * Menentukan warna & label status GIS peta berdasarkan progres tiket aktif (Model A).
     *
     * @return array{warna_key: string, kode_warna: string, status: string, label: string}
     */
    public function getStatusWarna(
        Desa $desa,
        ?Pengajuan $pengajuan = null,
        ?Permohonan $permohonan = null,
        ?Permohonan $permohonanByTiket = null,
    ): array {
        $effective = $this->determineEffectiveTicket($pengajuan, $permohonan, $permohonanByTiket);

        if ($effective === null) {
            return self::STATUS_META['abu_abu'];
        }

        $status = $effective['status'];

        if ($status === 'Selesai') {
            return self::STATUS_META['hijau'];
        }

        if (in_array($status, ['Disetujui', 'Dijadwalkan'], true)) {
            $meta = self::STATUS_META['kuning'];
            $meta['label'] = $status === 'Dijadwalkan'
                ? 'Penyaluran Air Sudah Dijadwalkan'
                : 'Disetujui BPBD (Menunggu Penyaluran)';

            return $meta;
        }

        if (in_array($status, ['Diajukan', 'Menunggu Verifikasi', 'Dalam Telaah', 'Revisi'], true)) {
            $meta = self::STATUS_META['merah'];
            $meta['label'] = match ($status) {
                'Revisi' => 'Perlu Revisi Berkas oleh Desa',
                'Dalam Telaah' => 'Sedang Ditelaah Verifikator BPBD',
                default => 'Pengajuan Menunggu Verifikasi BPBD',
            };

            return $meta;
        }

        return self::STATUS_META['abu_abu'];
    }

    /**
     * Menentukan skor & klasifikasi kelayakan AHP-SAW baku ambang 30 / 60 (PBI-026).
     *
     * @return array{
     *   skor: float|null,
     *   status: string,
     *   label: string,
     *   warna_key: string,
     *   badge_warna: string,
     *   sumber: string|null
     * }
     */
    public function getKelayakanData(
        Desa $desa,
        ?RiwayatCekKelayakan $riwayat = null,
        ?Permohonan $permohonan = null,
    ): array {
        $skor = null;
        $sumber = null;

        if ($riwayat !== null && $permohonan !== null) {
            if ($permohonan->created_at !== null && $riwayat->created_at !== null && $permohonan->created_at->gt($riwayat->created_at)) {
                $skor = (float) $permohonan->skor_ahp_saw;
                $sumber = 'permohonan';
            } else {
                $skor = (float) $riwayat->skor;
                $sumber = 'riwayat_cek_kelayakan';
            }
        } elseif ($riwayat !== null) {
            $skor = (float) $riwayat->skor;
            $sumber = 'riwayat_cek_kelayakan';
        } elseif ($permohonan !== null && $permohonan->skor_ahp_saw !== null) {
            $skor = (float) $permohonan->skor_ahp_saw;
            $sumber = 'permohonan';
        }

        if ($skor === null) {
            return [
                'skor' => null,
                'status' => 'Belum Ada Skor',
                'label' => 'Belum Ada Skor AHP-SAW',
                'warna_key' => 'abu_abu',
                'badge_warna' => '#6B7280',
                'sumber' => null,
            ];
        }

        $kategori = $this->cekKelayakanService->getStatus($skor);
        $formattedSkor = number_format($skor, 2, ',', '.');

        return match ($kategori) {
            'kritis' => [
                'skor' => round($skor, 2),
                'status' => 'Kritis',
                'label' => "Kritis ({$formattedSkor})",
                'warna_key' => 'merah',
                'badge_warna' => '#DC2626',
                'sumber' => $sumber,
            ],
            'waspada' => [
                'skor' => round($skor, 2),
                'status' => 'Waspada',
                'label' => "Waspada ({$formattedSkor})",
                'warna_key' => 'kuning',
                'badge_warna' => '#D97706',
                'sumber' => $sumber,
            ],
            default => [
                'skor' => round($skor, 2),
                'status' => 'Aman',
                'label' => "Aman ({$formattedSkor})",
                'warna_key' => 'hijau',
                'badge_warna' => '#059669',
                'sumber' => $sumber,
            ],
        };
    }

    /**
     * Muat batch data terkait per kode_desa agar query peta cepat.
     *
     * @return array{
     *   pengajuan: Collection<string, Pengajuan>,
     *   permohonan_by_desa: Collection<string, Permohonan>,
     *   permohonan_by_tiket: Collection<string, Permohonan>,
     *   riwayat: Collection<string, RiwayatCekKelayakan>
     * }
     */
    private function preloadRelatedData(Collection $kodeDesas): array
    {
        $codes = $kodeDesas->filter()->unique()->values()->all();

        $pengajuanMap = collect();
        $permohonanByDesa = collect();
        $permohonanByTiket = collect();
        $riwayatMap = collect();

        if (empty($codes)) {
            return [
                'pengajuan' => $pengajuanMap,
                'permohonan_by_desa' => $permohonanByDesa,
                'permohonan_by_tiket' => $permohonanByTiket,
                'riwayat' => $riwayatMap,
            ];
        }

        if (Schema::hasTable('pengajuan')) {
            $withRelations = Schema::hasTable('riwayat_cek_kelayakan') ? ['riwayatKelayakan'] : [];
            $pengajuanMap = Pengajuan::with($withRelations)
                ->whereIn('kode_desa', $codes)
                ->where('status', '!=', 'Draf')
                ->orderByDesc('tanggal_pengajuan')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->get()
                ->unique('kode_desa')
                ->keyBy('kode_desa');
        }

        if (Schema::hasTable('permohonans')) {
            $permohonans = Permohonan::whereIn('kode_desa', $codes)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->get();

            $permohonanByDesa = $permohonans->unique('kode_desa')->keyBy('kode_desa');
            $permohonanByTiket = $permohonans->filter(fn (Permohonan $p) => ! empty($p->nomor_tiket))
                ->unique('nomor_tiket')
                ->keyBy('nomor_tiket');
        }

        if (Schema::hasTable('riwayat_cek_kelayakan')) {
            $riwayatMap = RiwayatCekKelayakan::whereIn('kode_desa', $codes)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->get()
                ->unique('kode_desa')
                ->keyBy('kode_desa');
        }

        return [
            'pengajuan' => $pengajuanMap,
            'permohonan_by_desa' => $permohonanByDesa,
            'permohonan_by_tiket' => $permohonanByTiket,
            'riwayat' => $riwayatMap,
        ];
    }

    /**
     * Ekstrak relasi terbaru milik satu desa dari hasil batch preload.
     *
     * @return array{
     *   pengajuan: Pengajuan|null,
     *   permohonan: Permohonan|null,
     *   permohonan_by_tiket: Permohonan|null,
     *   riwayat: RiwayatCekKelayakan|null
     * }
     */
    private function resolveDesaContext(Desa $desa, array $context): array
    {
        /** @var Pengajuan|null $pengajuan */
        $pengajuan = $context['pengajuan']->get($desa->kode_desa);
        /** @var Permohonan|null $permohonanLatest */
        $permohonanLatest = $context['permohonan_by_desa']->get($desa->kode_desa);
        /** @var Permohonan|null $permohonanByTiket */
        $permohonanByTiket = ($pengajuan && ! empty($pengajuan->nomor_tiket))
            ? $context['permohonan_by_tiket']->get($pengajuan->nomor_tiket)
            : null;
        /** @var RiwayatCekKelayakan|null $latestRiwayat */
        $latestRiwayat = $context['riwayat']->get($desa->kode_desa);

        $preferredRiwayat = ($pengajuan?->relationLoaded('riwayatKelayakan') && $pengajuan->riwayatKelayakan)
            ? $pengajuan->riwayatKelayakan
            : $latestRiwayat;

        return [
            'pengajuan' => $pengajuan,
            'permohonan' => $permohonanByTiket ?? $permohonanLatest,
            'permohonan_by_tiket' => $permohonanByTiket,
            'riwayat' => $preferredRiwayat,
        ];
    }

    /**
     * Format satu item marker ringkas untuk JSON /admin/pemetaan/data.
     *
     * @return array<string, mixed>
     */
    private function formatMarkerItem(Desa $desa, array $context): array
    {
        $resolved = $this->resolveDesaContext($desa, $context);
        $statusGis = $this->getStatusWarna(
            $desa,
            $resolved['pengajuan'],
            $resolved['permohonan'],
            $resolved['permohonan_by_tiket']
        );
        $kelayakan = $this->getKelayakanData(
            $desa,
            $resolved['riwayat'],
            $resolved['permohonan']
        );
        $pengajuanRingkas = $this->buildPengajuanSummary(
            $resolved['pengajuan'],
            $resolved['permohonan'],
            $resolved['permohonan_by_tiket'],
            $statusGis
        );

        return [
            'kode_desa' => $desa->kode_desa,
            'nama_desa' => $desa->nama_desa,
            'kecamatan' => $desa->kecamatan,
            'latitude' => (float) $desa->latitude,
            'longitude' => (float) $desa->longitude,
            'titik_drop_air' => $desa->titik_drop_air,
            'status_gis' => $statusGis,
            'kelayakan' => $kelayakan,
            'pengajuan_aktif' => $pengajuanRingkas,
        ];
    }

    /**
     * Tentukan sumber tiket efektif (status & model sumber) di antara F-03 dan F-04.
     *
     * @return array{status: string, pengajuan: Pengajuan|null, permohonan: Permohonan|null}|null
     */
    private function determineEffectiveTicket(
        ?Pengajuan $pengajuan,
        ?Permohonan $permohonan,
        ?Permohonan $permohonanByTiket = null,
    ): ?array {
        if ($pengajuan !== null) {
            if (
                $permohonan !== null
                && $permohonan->nomor_tiket !== $pengajuan->nomor_tiket
                && $permohonan->created_at !== null
            ) {
                $pengajuanTime = $pengajuan->tanggal_pengajuan ?? $pengajuan->created_at;
                if ($pengajuanTime === null || $permohonan->created_at->gt($pengajuanTime)) {
                    return [
                        'status' => $permohonan->status,
                        'pengajuan' => null,
                        'permohonan' => $permohonan,
                    ];
                }
            }

            $status = $pengajuan->status;

            if ($status === 'Ditolak') {
                return null;
            }

            if (in_array($status, ['Dijadwalkan', 'Selesai'], true)) {
                return [
                    'status' => $status,
                    'pengajuan' => $pengajuan,
                    'permohonan' => $permohonanByTiket ?? $permohonan,
                ];
            }

            if ($permohonanByTiket !== null && in_array($permohonanByTiket->status, ['Disetujui', 'Revisi', 'Ditolak'], true)) {
                if ($permohonanByTiket->status === 'Ditolak') {
                    return null;
                }

                $status = $permohonanByTiket->status;
            }

            return [
                'status' => $status,
                'pengajuan' => $pengajuan,
                'permohonan' => $permohonanByTiket ?? $permohonan,
            ];
        }

        if ($permohonan !== null) {
            if ($permohonan->status === 'Ditolak') {
                return null;
            }

            return [
                'status' => $permohonan->status,
                'pengajuan' => null,
                'permohonan' => $permohonan,
            ];
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function buildPengajuanSummary(
        ?Pengajuan $pengajuan,
        ?Permohonan $permohonan,
        ?Permohonan $permohonanByTiket,
        array $statusGis,
    ): ?array {
        $effective = $this->determineEffectiveTicket($pengajuan, $permohonan, $permohonanByTiket);
        if ($effective === null) {
            return null;
        }

        /** @var Pengajuan|null $p */
        $p = $effective['pengajuan'];
        /** @var Permohonan|null $pm */
        $pm = $effective['permohonan'];

        $jumlahKk = $p !== null ? (int) ($p->jumlah_kk ?? 0) : (int) ($pm?->kk_terdampak ?? 0);
        $estimasiLiter = max(5000, $jumlahKk * 100);
        $estimasiTangki = $p !== null
            ? $p->estimasiTangki()
            : max((int) ($pm?->kebutuhan_tangki ?? 1), (int) ceil($estimasiLiter / 5000));

        return [
            'nomor_tiket' => $p?->nomor_tiket ?? $pm?->nomor_tiket,
            'status' => $effective['status'],
            'badge_warna' => $statusGis['kode_warna'],
            'estimasi_liter' => $estimasiLiter,
            'estimasi_tangki' => $estimasiTangki,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function buildPengajuanDetail(
        Desa $desa,
        ?Pengajuan $pengajuan,
        ?Permohonan $permohonan,
        ?Permohonan $permohonanByTiket,
        array $statusGis,
    ): ?array {
        $effective = $this->determineEffectiveTicket($pengajuan, $permohonan, $permohonanByTiket);
        if ($effective === null) {
            return null;
        }

        /** @var Pengajuan|null $p */
        $p = $effective['pengajuan'];
        /** @var Permohonan|null $pm */
        $pm = $effective['permohonan'];

        $jumlahKk = $p !== null ? (int) ($p->jumlah_kk ?? 0) : (int) ($pm?->kk_terdampak ?? 0);
        $estimasiLiter = max(5000, $jumlahKk * 100);
        $estimasiTangki = $p !== null
            ? $p->estimasiTangki()
            : max((int) ($pm?->kebutuhan_tangki ?? 1), (int) ceil($estimasiLiter / 5000));

        $tanggal = $p?->tanggal_pengajuan ?? $p?->created_at ?? $pm?->created_at;

        return [
            'nomor_tiket' => $p?->nomor_tiket ?? $pm?->nomor_tiket,
            'status' => $effective['status'],
            'badge_warna' => $statusGis['kode_warna'],
            'jumlah_kk' => $jumlahKk,
            'hari_tanpa_air' => $p !== null ? (int) ($p->hari_tanpa_air ?? 0) : (int) ($pm?->hari_tanpa_air ?? 0),
            'jarak_sumber_air' => $p !== null ? (int) ($p->jarak_sumber_air ?? 0) : (int) ($pm?->jarak_sumber_air ?? 0),
            'estimasi_liter' => $estimasiLiter,
            'estimasi_tangki' => $estimasiTangki,
            'alasan_pengajuan' => $p?->alasan_pengajuan ?? $pm?->alasan_permohonan,
            'lokasi_pengantaran' => $p?->lokasi_pengantaran ?? $desa->titik_drop_air,
            'tanggal_pengajuan' => $tanggal ? $tanggal->format('d M Y, H:i') . ' WIB' : null,
            'permohonan_id' => $pm?->id,
            'verifikasi_url' => $pm !== null ? route('verifikasi.show', $pm->id) : null,
        ];
    }

    private function hasValidCoordinates(Desa $desa): bool
    {
        return $desa->latitude !== null
            && $desa->longitude !== null
            && (float) $desa->latitude !== 0.0
            && (float) $desa->longitude !== 0.0;
    }
}

