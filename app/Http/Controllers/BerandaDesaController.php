<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\RiwayatCekKelayakan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BerandaDesaController extends Controller
{
    /**
     * Tampilkan halaman Beranda Desa (Layar utama perwakilan desa).
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        // Ambil data desa tertaut atau fallback ke desa pertama jika belum tertaut
        $desa = $user?->desa;
        if (! $desa) {
            $sessionKode = session('active_desa_kode');
            if ($sessionKode) {
                $desa = Desa::where('kode_desa', $sessionKode)->first();
            }
        }

        // 1. Data Hasil Cek Kelayakan Terakhir (F-02)
        $latestKelayakan = $desa ? $desa->latestRiwayatKelayakan : null;
        $statusKelayakan = $latestKelayakan ? ucfirst(strtolower($latestKelayakan->status)) : null; // 'Aman' | 'Waspada' | 'Kritis' | null
        $tanggalCek = $latestKelayakan?->created_at 
            ? Carbon::parse($latestKelayakan->created_at)->translatedFormat('d F Y, H:i') 
            : null;

        // 2. Data Pengajuan Terakhir / Aktif (F-03)
        $latestPengajuan = $desa ? $desa->pengajuan()->latest()->first() : null;

        // 3. Tombol Aksi Kontekstual Utama (Contextual Primary CTA)
        $cta = $this->resolveContextualAction($latestKelayakan, $latestPengajuan);

        // 4. Status Progress Stepper (5 Tahapan)
        $stepper = $this->calculateProgressStepper($latestKelayakan, $latestPengajuan);

        // 5. Timeline Pengajuan Terakhir
        $timeline = $this->buildSubmissionTimeline($latestPengajuan);

        // 6. Ringkasan Demografi & Kelengkapan Profil Desa
        $ringkasan = $this->buildVillageSummary($desa);

        // 7. Aktivitas Terbaru (3 data riwayat / notifikasi)
        $aktivitas = $this->getRecentActivities($desa, $latestKelayakan, $latestPengajuan);

        return view('desa.dashboard', compact(
            'desa',
            'latestKelayakan',
            'statusKelayakan',
            'tanggalCek',
            'latestPengajuan',
            'cta',
            'stepper',
            'timeline',
            'ringkasan',
            'aktivitas'
        ));
    }

    /**
     * Menentukan SATU tombol utama kontekstual berdasarkan kondisi desa saat ini:
     * - Belum cek kelayakan -> "Mulai Cek Kelayakan"
     * - Waspada/Kritis & belum ajukan -> "Ajukan Bantuan Air Bersih"
     * - Pengajuan sudah ada -> "Lacak Pengajuan"
     * - Status Aman -> "Cek Ulang Kelayakan"
     */
    private function resolveContextualAction(?RiwayatCekKelayakan $kelayakan, ?Pengajuan $pengajuan): array
    {
        // Kondisi 1: Belum pernah cek kelayakan
        if (! $kelayakan) {
            return [
                'type' => 'cek_kelayakan',
                'label' => 'Mulai Cek Kelayakan',
                'url' => route('cek-kelayakan.index'),
                'icon' => 'fa-calculator',
                'description' => 'Periksa kondisi kekurangan air desa Anda sekarang untuk penentuan prioritas bantuan.',
                'show_recheck' => false,
            ];
        }

        $statusLower = strtolower($kelayakan->status);

        // Kondisi 2: Status darurat (Waspada/Kritis) dan belum ada pengajuan aktif
        if (in_array($statusLower, ['waspada', 'kritis'], true) && ! $pengajuan) {
            return [
                'type' => 'ajukan_bantuan',
                'label' => 'Ajukan Bantuan Air Bersih',
                'url' => route('pengajuan.create'),
                'icon' => 'fa-file-circle-plus',
                'description' => 'Desa Anda memenuhi syarat darurat. Segera kirimkan permohonan pasokan tangki air ke BPBD.',
                'show_recheck' => true,
            ];
        }

        // Kondisi 3: Sudah ada pengajuan
        if ($pengajuan) {
            return [
                'type' => 'riwayat_pengajuan',
                'label' => 'Riwayat Pengajuan',
                'url' => route('pengajuan.index'),
                'icon' => 'fa-file-lines',
                'description' => 'Tiket #' . $pengajuan->nomor_tiket . ' telah tercatat di sistem. Kelola draf dan riwayat pengajuan desa.',
                'show_recheck' => true,
            ];
        }

        // Kondisi 4: Status Aman (tidak butuh bantuan darurat saat ini)
        return [
            'type' => 'cek_ulang',
            'label' => 'Cek Ulang Kelayakan',
            'url' => route('cek-kelayakan.index'),
            'icon' => 'fa-rotate-right',
            'description' => 'Kondisi air desa saat ini tergolong aman. Cek kembali jika pasokan air mulai menipis.',
            'show_recheck' => false,
        ];
    }

    /**
     * Hitung progres 5 tahapan:
     * 1. Cek Kelayakan -> 2. Pengajuan -> 3. Verifikasi -> 4. Penyaluran -> 5. Selesai
     */
    private function calculateProgressStepper(?RiwayatCekKelayakan $kelayakan, ?Pengajuan $pengajuan): array
    {
        $steps = [
            1 => ['label' => 'Cek Kelayakan', 'icon' => 'fa-calculator', 'status' => 'pending'],
            2 => ['label' => 'Pengajuan', 'icon' => 'fa-file-circle-plus', 'status' => 'pending'],
            3 => ['label' => 'Verifikasi', 'icon' => 'fa-clipboard-check', 'status' => 'pending'],
            4 => ['label' => 'Penyaluran', 'icon' => 'fa-truck-droplet', 'status' => 'pending'],
            5 => ['label' => 'Selesai', 'icon' => 'fa-circle-check', 'status' => 'pending'],
        ];

        if (! $kelayakan) {
            $steps[1]['status'] = 'current';
            return $steps;
        }

        // Cek kelayakan selesai
        $steps[1]['status'] = 'completed';

        if (! $pengajuan) {
            if (in_array(strtolower($kelayakan->status), ['waspada', 'kritis'], true)) {
                $steps[2]['status'] = 'current';
            } else {
                // Aman, berhenti di Cek Kelayakan
                $steps[1]['status'] = 'completed';
            }
            return $steps;
        }

        // Ada pengajuan
        $statusPengajuan = $pengajuan->status;

        if ($statusPengajuan === 'Draf') {
            $steps[2]['status'] = 'current';
        } elseif (in_array($statusPengajuan, ['Diajukan', 'Dalam Telaah', 'Revisi'], true)) {
            $steps[2]['status'] = 'completed';
            $steps[3]['status'] = 'current';
        } elseif (in_array($statusPengajuan, ['Disetujui', 'Dijadwalkan', 'Penyaluran'], true)) {
            $steps[2]['status'] = 'completed';
            $steps[3]['status'] = 'completed';
            $steps[4]['status'] = 'current';
        } elseif ($statusPengajuan === 'Selesai') {
            $steps[2]['status'] = 'completed';
            $steps[3]['status'] = 'completed';
            $steps[4]['status'] = 'completed';
            $steps[5]['status'] = 'completed';
        } elseif ($statusPengajuan === 'Ditolak') {
            $steps[2]['status'] = 'completed';
            $steps[3]['status'] = 'failed';
        }

        return $steps;
    }

    /**
     * Mini vertical timeline untuk pengajuan terakhir
     */
    private function buildSubmissionTimeline(?Pengajuan $pengajuan): ?array
    {
        if (! $pengajuan) {
            return null;
        }

        $createdAt = $pengajuan->tanggal_pengajuan ?: $pengajuan->created_at;
        $status = $pengajuan->status;

        return [
            'nomor_tiket' => $pengajuan->nomor_tiket,
            'status' => $status,
            'items' => [
                [
                    'title' => 'Permohonan Diajukan',
                    'time' => $createdAt ? Carbon::parse($createdAt)->translatedFormat('d M Y, H:i') : '-',
                    'desc' => 'Dokumen pengajuan berhasil dikirimkan oleh perwakilan desa.',
                    'state' => 'completed',
                ],
                [
                    'title' => 'Verifikasi BPBD',
                    'time' => in_array($status, ['Dalam Telaah', 'Disetujui', 'Dijadwalkan', 'Penyaluran', 'Selesai'], true) ? 'Sedang ditelaah / Disetujui' : 'Menunggu antrean telaah',
                    'desc' => in_array($status, ['Disetujui', 'Dijadwalkan', 'Penyaluran', 'Selesai'], true) 
                        ? 'Pengajuan telah diverifikasi dan disetujui BPBD Purbalingga.' 
                        : ($status === 'Revisi' ? 'Membutuhkan perbaikan data dari desa.' : ($status === 'Ditolak' ? 'Permohonan tidak dapat disetujui.' : 'Petugas BPBD sedang meninjau kelayakan dan data lapangan.')),
                    'state' => in_array($status, ['Disetujui', 'Dijadwalkan', 'Penyaluran', 'Selesai'], true) 
                        ? 'completed' 
                        : (in_array($status, ['Diajukan', 'Dalam Telaah'], true) ? 'current' : ($status === 'Revisi' ? 'warning' : 'pending')),
                ],
                [
                    'title' => 'Estimasi Penyaluran Armada',
                    'time' => in_array($status, ['Dijadwalkan', 'Penyaluran', 'Selesai'], true) ? 'Jadwal armada siap' : 'Menunggu persetujuan',
                    'desc' => in_array($status, ['Dijadwalkan', 'Penyaluran', 'Selesai'], true)
                        ? 'Armada tangki air bersih dialokasikan ke titik drop tandon desa.'
                        : 'Jadwal dan armada tangki akan ditentukan setelah verifikasi disetujui.',
                    'state' => in_array($status, ['Dijadwalkan', 'Penyaluran'], true) 
                        ? 'current' 
                        : ($status === 'Selesai' ? 'completed' : 'pending'),
                ],
            ]
        ];
    }

    /**
     * Hitung kelengkapan profil dan demografi desa
     */
    private function buildVillageSummary(?Desa $desa): array
    {
        if (! $desa) {
            return [
                'total_kk' => 0,
                'total_jiwa' => 0,
                'balita' => 0,
                'lansia' => 0,
                'disabilitas' => 0,
                'titik_drop' => 'Belum ditentukan',
                'has_coords' => false,
                'lat' => -7.3920,
                'lng' => 109.3638,
                'nama_pj' => 'Belum ada',
                'no_wa' => '-',
                'wa_url' => '#',
                'completeness' => 0,
            ];
        }

        $fields = [
            'nama_desa' => ! empty($desa->nama_desa),
            'kecamatan' => ! empty($desa->kecamatan),
            'kode_desa' => ! empty($desa->kode_desa),
            'jumlah_kk_total' => ! empty($desa->jumlah_kk_total),
            'jumlah_jiwa_total' => ! empty($desa->jumlah_jiwa_total),
            'jumlah_balita' => isset($desa->jumlah_balita),
            'jumlah_lansia' => isset($desa->jumlah_lansia),
            'jumlah_disabilitas' => isset($desa->jumlah_disabilitas),
            'titik_drop_air' => ! empty($desa->titik_drop_air),
            'latitude' => ! is_null($desa->latitude) && (float) $desa->latitude !== 0.0,
            'longitude' => ! is_null($desa->longitude) && (float) $desa->longitude !== 0.0,
            'nama_pj_darurat' => ! empty($desa->nama_pj_darurat),
            'no_wa_darurat' => ! empty($desa->no_wa_darurat),
        ];

        $filledCount = count(array_filter($fields));
        $completeness = (int) round(($filledCount / count($fields)) * 100);

        // Normalisasi nomor WA ke format 62
        $rawWa = (string) $desa->no_wa_darurat;
        $normalizedWa = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($normalizedWa, '0')) {
            $normalizedWa = '62' . substr($normalizedWa, 1);
        } elseif (str_starts_with($normalizedWa, '8')) {
            $normalizedWa = '62' . $normalizedWa;
        }

        $waText = rawurlencode("Halo, saya perwakilan Desa {$desa->nama_desa}, Kecamatan {$desa->kecamatan} ingin berkoordinasi mengenai bantuan air bersih LinggaAir.");
        $waUrl = ! empty($normalizedWa) ? "https://wa.me/{$normalizedWa}?text={$waText}" : '#';

        $hasCoords = ! is_null($desa->latitude) && ! is_null($desa->longitude) 
            && (float) $desa->latitude !== 0.0 && (float) $desa->longitude !== 0.0;

        return [
            'total_kk' => (int) ($desa->jumlah_kk_total ?? 0),
            'total_jiwa' => (int) ($desa->jumlah_jiwa_total ?? 0),
            'balita' => (int) ($desa->jumlah_balita ?? 0),
            'lansia' => (int) ($desa->jumlah_lansia ?? 0),
            'disabilitas' => (int) ($desa->jumlah_disabilitas ?? 0),
            'titik_drop' => $desa->titik_drop_air ?: 'Belum ditentukan',
            'has_coords' => $hasCoords,
            'lat' => $hasCoords ? (float) $desa->latitude : -7.3920,
            'lng' => $hasCoords ? (float) $desa->longitude : 109.3638,
            'nama_pj' => $desa->nama_pj_darurat ?: 'Belum ditentukan',
            'no_wa' => $desa->no_wa_darurat ?: '-',
            'wa_url' => $waUrl,
            'completeness' => $completeness,
        ];
    }

    /**
     * Ambil 3 aktivitas terbaru dari riwayat riil dan fallback mock
     */
    private function getRecentActivities(?Desa $desa, ?RiwayatCekKelayakan $kelayakan, ?Pengajuan $pengajuan): array
    {
        $activities = [];

        if ($pengajuan) {
            $activities[] = [
                'title' => 'Pengajuan Bantuan Air Terkirim',
                'desc' => "Tiket #{$pengajuan->nomor_tiket} diajukan untuk {$pengajuan->jumlah_kk} KK terdampak.",
                'time' => Carbon::parse($pengajuan->created_at)->diffForHumans(),
                'icon' => 'fa-file-circle-check',
                'color' => 'blue',
            ];
        }

        if ($kelayakan) {
            $activities[] = [
                'title' => 'Cek Kelayakan Mandiri',
                'desc' => "Hasil evaluasi status kekeringan bernilai {$kelayakan->status} (Skor: " . number_format($kelayakan->skor, 1) . ").",
                'time' => Carbon::parse($kelayakan->created_at)->diffForHumans(),
                'icon' => 'fa-scale-balanced',
                'color' => strtolower($kelayakan->status) === 'kritis' ? 'red' : (strtolower($kelayakan->status) === 'waspada' ? 'amber' : 'green'),
            ];
        }

        if ($desa && $desa->updated_at) {
            $activities[] = [
                'title' => 'Data Profil Desa Diperbarui',
                'desc' => "Informasi demografi dan titik drop desa {$desa->nama_desa} telah disimpan.",
                'time' => Carbon::parse($desa->updated_at)->diffForHumans(),
                'icon' => 'fa-id-card',
                'color' => 'blue',
            ];
        }

        // TODO: Ganti dengan tabel notifikasi sistem terpusat jika model notifikasi sudah tersedia di backend
        if (count($activities) < 3) {
            $fallbackMock = [
                [
                    'title' => 'Panduan Distribusi Air Bersih',
                    'desc' => 'BPBD Purbalingga merilis petunjuk teknis koordinasi titik drop air darurat.',
                    'time' => '1 hari lalu',
                    'icon' => 'fa-circle-info',
                    'color' => 'blue',
                ],
                [
                    'title' => 'Akun Perwakilan Desa Aktif',
                    'desc' => 'Selamat datang di Sistem Informasi Tanggap Air Bersih LinggaAir.',
                    'time' => '3 hari lalu',
                    'icon' => 'fa-check',
                    'color' => 'green',
                ],
            ];

            foreach ($fallbackMock as $mock) {
                if (count($activities) >= 3) break;
                $activities[] = $mock;
            }
        }

        return array_slice($activities, 0, 3);
    }

    /**
     * Tampilkan form edit profil desa untuk perwakilan desa.
     */
    public function editProfile(): View|RedirectResponse
    {
        $desa = Auth::user()->desa;
        if (! $desa) {
            return redirect()->route('desa.dashboard')->with('error', 'Akun Anda belum tertaut dengan data desa.');
        }

        return view('desa.profile-edit', compact('desa'));
    }

    /**
     * Update data profil desa oleh perwakilan desa.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $desa = Auth::user()->desa;
        if (! $desa) {
            return redirect()->route('desa.dashboard')->with('error', 'Akun Anda belum tertaut dengan data desa.');
        }

        $validated = $request->validate([
            'jumlah_kk_total' => 'nullable|integer|min:0',
            'jumlah_jiwa_total' => 'nullable|integer|min:0',
            'jumlah_balita' => 'nullable|integer|min:0',
            'jumlah_lansia' => 'nullable|integer|min:0',
            'jumlah_disabilitas' => 'nullable|integer|min:0',
            'titik_drop_air' => 'nullable|string|max:255',
            'nama_pj_darurat' => 'nullable|string|max:100',
            'no_wa_darurat' => 'nullable|string|max:30',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $desa->update($validated);

        return redirect()->route('desa.dashboard')->with('success', 'Data profil desa berhasil diperbarui!');
    }
}
