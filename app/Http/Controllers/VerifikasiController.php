<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\KeputusanPermohonan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    /**
     * PBI-010: Menampilkan antrean pengajuan terurut skor prioritas AHP-SAW (DESC)
     * Sinkron F-02 -> F-04: Hanya menampilkan permohonan yang lolos skrining kelayakan (Skor >= 40.00)
     */
    public function index(Request $request)
    {
        // Bersihkan data permohonan berstatus 'Aman' (< 40.00) jika masih tersisa dari seeding lama
        // Sesuai aturan PBI-005 AC 2: Status Aman dicegat di F-02 dan TIDAK BISA mengajukan ke F-03/F-04
        Permohonan::where('skor_ahp_saw', '<', 40.00)->delete();

        // Query antrean pengajuan diurutkan dari skor AHP-SAW tertinggi ke terendah
        // Memastikan hanya permohonan yang berkategori KRITIS & WASPADA (Skor >= 40.00) yang tampil di antrean verifikasi BPBD
        $query = Permohonan::with('desa')->where('skor_ahp_saw', '>=', 40.00);

        // Filter status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default menampilkan yang butuh tindakan verifikasi
            $query->whereIn('status', ['Menunggu Verifikasi', 'Revisi']);
        }

        // Filter pencarian nama desa / nomor tiket
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_tiket', 'like', "%{$search}%")
                  ->orWhereHas('desa', function ($qDesa) use ($search) {
                      $qDesa->where('nama_desa', 'like', "%{$search}%")
                            ->orWhere('kecamatan', 'like', "%{$search}%");
                  });
            });
        }

        // KUNCI PBI-010: Terurut otomatis dari skor prioritas tertinggi ke terendah
        $antrean = $query->orderBy('skor_ahp_saw', 'desc')->get();

        // Kartu ringkasan metrik verifikasi (hanya menghitung yang lolos skrining >= 40.00)
        $ringkasan = [
            'total_pengajuan' => Permohonan::where('skor_ahp_saw', '>=', 40.00)->count(),
            'menunggu_verifikasi' => Permohonan::where('skor_ahp_saw', '>=', 40.00)->where('status', 'Menunggu Verifikasi')->count(),
            'disetujui' => Permohonan::where('skor_ahp_saw', '>=', 40.00)->where('status', 'Disetujui')->count(),
            'perlu_tindak_lanjut' => Permohonan::where('skor_ahp_saw', '>=', 40.00)->whereIn('status', ['Revisi', 'Ditolak'])->count(),
        ];

        return view('verifikasi.index', compact('antrean', 'ringkasan'));
    }

    /**
     * PBI-010: Menampilkan detail lengkap data permohonan
     */
    public function show($id)
    {
        $permohonan = Permohonan::with(['desa', 'keputusanPermohonans.user'])
            ->where('skor_ahp_saw', '>=', 40.00)
            ->findOrFail($id);

        return view('verifikasi.show', compact('permohonan'));
    }

    /**
     * PBI-011 & PBI-012: Memproses keputusan verifikasi (Disetujui, Ditolak, atau Revisi)
     * Beserta pencatatan riwayat audit trail log
     */
    public function prosesKeputusan(Request $request, $id)
    {
        $permohonan = Permohonan::with('desa')->findOrFail($id);

        // Validasi input
        $request->validate([
            'status_keputusan' => 'required|in:Disetujui,Ditolak,Revisi',
            'catatan_alasan' => 'required_if:status_keputusan,Ditolak,Revisi|nullable|string|min:5',
            'volume_air_disetujui' => 'nullable|numeric|min:0',
        ], [
            'status_keputusan.required' => 'Status keputusan verifikasi harus ditentukan.',
            'status_keputusan.in' => 'Status keputusan tidak valid.',
            'catatan_alasan.required_if' => 'Catatan alasan wajib diisi jika keputusan berupa Penolakan atau Permintaan Revisi berkas!',
            'catatan_alasan.min' => 'Catatan alasan minimal 5 karakter agar memberikan kejelasan bagi pihak pemohon.',
            'volume_air_disetujui.numeric' => 'Volume air yang disetujui harus berupa angka numerik (liter).',
        ]);

        // Verifikator BPBD (fallback jika belum login dengan session)
        $userId = auth()->id() ?? User::where('email', 'admin@bpbd.purbalinggakab.go.id')->value('id') ?? User::first()?->id;

        // Tentukan volume air disetujui (default: jumlah tangki * 5.000 liter standar tangki BPBD Purbalingga)
        $volumeAir = null;
        if ($request->status_keputusan === 'Disetujui') {
            $volumeAir = $request->filled('volume_air_disetujui') 
                ? (int) $request->volume_air_disetujui 
                : ($permohonan->kebutuhan_tangki * 5000);
        }

        // Tentukan catatan alasan default jika persetujuan tidak mengisi catatan
        $catatanAlasan = $request->catatan_alasan;
        if (empty($catatanAlasan) && $request->status_keputusan === 'Disetujui') {
            $catatanAlasan = 'Permohonan telah memenuhi indikator prioritas kedaruratan air bersih dan disetujui untuk pengiriman armada tangki.';
        }

        // Transaksi Database: Memastikan integritas data permohonan dan log audit
        DB::transaction(function () use ($permohonan, $request, $userId, $volumeAir, $catatanAlasan) {
            // 1. Simpan riwayat keputusan ke tabel audit trail (keputusan_permohonans)
            KeputusanPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'user_id' => $userId,
                'status_keputusan' => $request->status_keputusan,
                'volume_air_disetujui' => $volumeAir,
                'catatan_alasan' => $catatanAlasan,
            ]);

            // 2. Perbarui status permohonan utama
            $permohonan->update([
                'status' => $request->status_keputusan,
            ]);
        });

        // Pesan notifikasi sesuai jenis keputusan
        $pesanNotifikasi = match ($request->status_keputusan) {
            'Disetujui' => "Keputusan DISETUJUI untuk tiket {$permohonan->nomor_tiket} (Desa {$permohonan->desa->nama_desa}). Alokasi pasokan air: " . number_format($volumeAir) . " Liter.",
            'Ditolak' => "Keputusan DITOLAK untuk tiket {$permohonan->nomor_tiket} (Desa {$permohonan->desa->nama_desa}). Alasan penolakan telah tercatat pada log audit.",
            'Revisi' => "Permintaan REVISI BERKAS telah dikirim untuk tiket {$permohonan->nomor_tiket} (Desa {$permohonan->desa->nama_desa}).",
        };

        return redirect()->route('verifikasi.show', $permohonan->id)->with('success', $pesanNotifikasi);
    }
}
