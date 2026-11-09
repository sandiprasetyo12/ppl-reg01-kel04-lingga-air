<?php

namespace App\Http\Controllers;

use App\Models\BerkasPengajuan;
use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\SimulasiKelayakan;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    /**
     * PBI-008: Menampilkan daftar draf pengajuan dan status permohonan desa
     * Acceptance Criteria:
     * - Draf desa lain tidak terlihat
     * - Perangkat desa dapat melihat draf milik desanya
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $desa = $user->desa;

        if (!$desa) {
            abort(403, 'Akun Anda belum terhubung dengan data desa resmi.');
        }

        // Ambil hanya draf milik desa pengguna yang sedang login
        $drafList = Pengajuan::where('desa_id', $desa->id)
            ->where('status', 'Draf')
            ->with(['simulasi', 'berkas'])
            ->latest()
            ->paginate(10, ['*'], 'draf_page');

        // Ambil riwayat pengajuan yang sudah dikirim (non-draf)
        $riwayatList = Pengajuan::where('desa_id', $desa->id)
            ->where('status', '!=', 'Draf')
            ->with(['simulasi', 'berkas'])
            ->latest()
            ->paginate(10, ['*'], 'riwayat_page');

        $activeTicket = $desa->activeTicket();
        $hasActiveTicket = $desa->hasActiveTicket();

        return view('pengajuan.index', compact('desa', 'drafList', 'riwayatList', 'activeTicket', 'hasActiveTicket'));
    }

    /**
     * PBI-007: Menampilkan formulir penyusunan pengajuan baru
     * Formulir terisi otomatis (pre-filled) dari hasil cek kelayakan mandiri
     */
    public function create(Request $request): View
    {
        $user = Auth::user();
        $desa = $user->desa;

        if (!$desa) {
            abort(403, 'Akun Anda belum terhubung dengan data desa resmi.');
        }

        // Ambil hasil simulasi kelayakan (bisa via id atau yang terbaru)
        $simulasi = null;
        if ($request->has('simulasi_id')) {
            $simulasi = SimulasiKelayakan::where('desa_id', $desa->id)
                ->where('id', $request->query('simulasi_id'))
                ->first();
        }

        if (!$simulasi) {
            $simulasi = $desa->latestSimulasi;
        }

        // Jika belum ada simulasi sama sekali, sediakan instance default agar form tetap berfungsi
        if (!$simulasi) {
            $simulasi = new SimulasiKelayakan([
                'desa_id' => $desa->id,
                'kk_terdampak' => 120,
                'jiwa_rentan' => ($desa->jumlah_balita + $desa->jumlah_lansia + $desa->jumlah_disabilitas),
                'hari_tanpa_air' => 14,
                'jarak_sumber_air' => 1200,
                'skor_ahp_saw' => 92.40,
                'status_kelayakan' => 'Kritis',
                'rekomendasi' => 'Segera ajukan bantuan pasokan air bersih untuk kebutuhan warga terdampak.',
            ]);
        }

        $hasActiveTicket = $desa->hasActiveTicket();
        $activeTicket = $desa->activeTicket();

        return view('pengajuan.create', compact('desa', 'simulasi', 'hasActiveTicket', 'activeTicket'));
    }

    /**
     * PBI-007 & PBI-009: Menyimpan pengajuan sebagai draf atau langsung mengirim ke antrean
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $desa = $user->desa;

        if (!$desa) {
            abort(403, 'Akun Anda belum terhubung dengan data desa resmi.');
        }

        $action = $request->input('action', 'draf'); // 'draf' atau 'kirim'

        // Validasi input
        $rules = [
            'simulasi_id' => ['nullable', 'exists:simulasi_kelayakan,id'],
            'jumlah_kk' => ['required', 'integer', 'min:1'],
            'hari_tanpa_air' => ['required', 'integer', 'min:1', 'max:365'],
            'jarak_sumber_air' => ['required', 'integer', 'min:1', 'max:100000'],
            'alasan_pengajuan' => ['required', 'string', 'min:10', 'max:2000'],
            'lokasi_pengantaran' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'catatan_tambahan' => ['nullable', 'string', 'max:1000'],
            'foto_lapangan' => ['nullable', 'array', 'max:3'],
            'foto_lapangan.*' => ['file', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB, format JPG/PNG
            'surat_kepala_desa' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];

        $messages = [
            'jumlah_kk.required' => 'Jumlah KK terdampak wajib diisi.',
            'jumlah_kk.integer' => 'Jumlah KK terdampak harus berupa angka bulat positif.',
            'jumlah_kk.min' => 'Jumlah KK terdampak minimal 1 KK.',
            'hari_tanpa_air.required' => 'Lama hari tanpa air wajib diisi.',
            'hari_tanpa_air.min' => 'Lama hari tanpa air minimal 1 hari.',
            'jarak_sumber_air.required' => 'Jarak ke sumber air alternatif wajib diisi.',
            'jarak_sumber_air.min' => 'Jarak ke sumber air minimal 1 meter.',
            'alasan_pengajuan.required' => 'Alasan pengajuan wajib diisi untuk menjelaskan kondisi sumber air.',
            'alasan_pengajuan.min' => 'Alasan pengajuan minimal berisi 10 karakter penjelasan.',
            'foto_lapangan.max' => 'Maksimal hanya dapat mengunggah 3 foto bukti lapangan.',
            'foto_lapangan.*.file' => 'Berkas foto bukti lapangan tidak valid.',
            'foto_lapangan.*.mimes' => 'Sistem hanya menerima foto bukti kondisi lapangan berekstensi JPG atau PNG.',
            'foto_lapangan.*.max' => 'Ukuran setiap foto bukti lapangan maksimal 5 MB.',
            'surat_kepala_desa.mimes' => 'Surat pengantar Kepala Desa harus berupa dokumen PDF atau gambar JPG/PNG.',
            'surat_kepala_desa.max' => 'Ukuran surat pengantar Kepala Desa maksimal 5 MB.',
        ];

        $validated = $request->validate($rules, $messages);

        // Ambil data simulasi kelayakan yang terkait
        $simulasi = null;
        if (!empty($validated['simulasi_id'])) {
            $simulasi = SimulasiKelayakan::where('desa_id', $desa->id)->find($validated['simulasi_id']);
        }
        if (!$simulasi) {
            $simulasi = $desa->latestSimulasi;
        }

        // PBI-009: Jika aksi adalah 'kirim', jalankan validasi kelayakan dan tiket aktif
        if ($action === 'kirim') {
            // 1. Validasi hasil cek kelayakan harus Kritis atau Waspada
            if ($simulasi && $simulasi->status_kelayakan === 'Aman') {
                return back()->withInput()->withErrors([
                    'kelayakan' => 'Pengajuan ditolak. Status kelayakan desa adalah Aman. Pengajuan bantuan hanya dapat dikirim jika berstatus Kritis atau Waspada.',
                ]);
            }

            // 2. Acceptance Criteria: Sistem menolak pengajuan baru jika desa sudah punya tiket aktif (anti-spam)
            if ($desa->hasActiveTicket()) {
                $aktif = $desa->activeTicket();
                return back()->withInput()->withErrors([
                    'tiket_aktif' => 'Sistem menolak pengajuan baru. Desa ' . $desa->nama_desa . ' saat ini masih memiliki tiket aktif (' . ($aktif->nomor_tiket ?? 'Tiket Aktif') . ') dengan status: ' . $aktif->status . '.',
                ]);
            }

            // 3. Pastikan minimal ada 1 foto lapangan yang diunggah jika langsung dikirim
            if (!$request->hasFile('foto_lapangan') && empty($validated['foto_lapangan'])) {
                return back()->withInput()->withErrors([
                    'foto_lapangan' => 'Foto bukti kondisi lapangan wajib diunggah minimal 1 foto untuk pengiriman pengajuan.',
                ]);
            }
        }

        return DB::transaction(function () use ($desa, $simulasi, $validated, $request, $action) {
            $status = ($action === 'kirim') ? 'Diajukan' : 'Draf';
            $nomorTiket = ($action === 'kirim') ? Pengajuan::generateNomorTiket() : null;
            $tanggalPengajuan = ($action === 'kirim') ? Carbon::now() : null;

            // Pastikan data kebutuhan selalu sinkron dengan Cek Kelayakan (tidak dapat di-tamper)
            $jumlahKk = $simulasi ? $simulasi->kk_terdampak : $validated['jumlah_kk'];
            $hariTanpaAir = $simulasi ? $simulasi->hari_tanpa_air : $validated['hari_tanpa_air'];
            $jarakSumberAir = $simulasi ? $simulasi->jarak_sumber_air : $validated['jarak_sumber_air'];

            $pengajuan = Pengajuan::create([
                'nomor_tiket' => $nomorTiket,
                'desa_id' => $desa->id,
                'simulasi_id' => $simulasi?->id,
                'status' => $status,
                'jumlah_kk' => $jumlahKk,
                'hari_tanpa_air' => $hariTanpaAir,
                'jarak_sumber_air' => $jarakSumberAir,
                'alasan_pengajuan' => $validated['alasan_pengajuan'],
                'lokasi_pengantaran' => $validated['lokasi_pengantaran'] ?? $desa->lokasi_drop_point,
                'latitude' => $validated['latitude'] ?? $desa->latitude,
                'longitude' => $validated['longitude'] ?? $desa->longitude,
                'catatan_tambahan' => $validated['catatan_tambahan'] ?? null,
                'tanggal_pengajuan' => $tanggalPengajuan,
            ]);

            // Simpan foto bukti lapangan (maks 3 foto)
            if ($request->hasFile('foto_lapangan')) {
                foreach ($request->file('foto_lapangan') as $index => $foto) {
                    if ($index >= 3) break;
                    $path = $foto->store('pengajuan/foto', 'public');
                    BerkasPengajuan::create([
                        'pengajuan_id' => $pengajuan->id,
                        'jenis_berkas' => 'foto_kondisi',
                        'nama_file' => $foto->getClientOriginalName(),
                        'path_file' => $path,
                        'mime_type' => $foto->getMimeType() ?: 'image/jpeg',
                        'ukuran_file' => $foto->getSize(),
                        'keterangan' => 'Foto bukti kondisi lapangan #' . ($index + 1),
                    ]);
                }
            }

            // Simpan surat pengantar Kepala Desa
            if ($request->hasFile('surat_kepala_desa')) {
                $surat = $request->file('surat_kepala_desa');
                $path = $surat->store('pengajuan/surat', 'public');
                BerkasPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'jenis_berkas' => 'surat_kepala_desa',
                    'nama_file' => $surat->getClientOriginalName(),
                    'path_file' => $path,
                    'mime_type' => $surat->getMimeType() ?: 'application/pdf',
                    'ukuran_file' => $surat->getSize(),
                    'keterangan' => 'Surat Pengantar Resmi Kepala Desa berstempel basah',
                ]);
            }

            if ($action === 'kirim') {
                return redirect()->route('pengajuan.success', $pengajuan->id)
                    ->with('success', 'Pengajuan bantuan air bersih berhasil dikirim ke antrean Admin.');
            }

            return redirect()->route('pengajuan.index')
                ->with('success', 'Draf pengajuan berhasil disimpan. Anda dapat melihat, mengubah, atau mengirimkannya sewaktu-waktu.');
        });
    }

    /**
     * PBI-008: Menampilkan detail pengajuan / draf
     * Acceptance Criteria: Draf desa lain tidak terlihat
     */
    public function show(Pengajuan $pengajuan): View
    {
        Gate::authorize('view', $pengajuan);

        $pengajuan->load(['desa', 'simulasi', 'berkas']);
        $desa = $pengajuan->desa;

        return view('pengajuan.show', compact('pengajuan', 'desa'));
    }

    /**
     * PBI-008: Menampilkan formulir ubah draf
     * Acceptance Criteria:
     * - Draf yang sudah dikirim tidak dapat diubah
     * - Perangkat desa dapat mengubah draf milik desanya sebelum dikirim
     */
    public function edit(Pengajuan $pengajuan): View
    {
        Gate::authorize('update', $pengajuan);

        $pengajuan->load(['desa', 'simulasi', 'berkas']);
        $desa = $pengajuan->desa;
        $simulasi = $pengajuan->simulasi ?: $desa->latestSimulasi;

        return view('pengajuan.edit', compact('pengajuan', 'desa', 'simulasi'));
    }

    /**
     * PBI-008: Memperbarui isi draf pengajuan
     */
    public function update(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        Gate::authorize('update', $pengajuan);

        $desa = $pengajuan->desa;
        $action = $request->input('action', 'draf');

        $rules = [
            'jumlah_kk' => ['required', 'integer', 'min:1'],
            'hari_tanpa_air' => ['required', 'integer', 'min:1', 'max:365'],
            'jarak_sumber_air' => ['required', 'integer', 'min:1', 'max:100000'],
            'alasan_pengajuan' => ['required', 'string', 'min:10', 'max:2000'],
            'lokasi_pengantaran' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'catatan_tambahan' => ['nullable', 'string', 'max:1000'],
            'foto_lapangan' => ['nullable', 'array', 'max:3'],
            'foto_lapangan.*' => ['file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'surat_kepala_desa' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];

        $messages = [
            'jumlah_kk.required' => 'Jumlah KK terdampak wajib diisi.',
            'alasan_pengajuan.required' => 'Alasan pengajuan wajib diisi.',
            'foto_lapangan.*.mimes' => 'Sistem hanya menerima foto bukti kondisi lapangan berekstensi JPG atau PNG.',
            'foto_lapangan.*.max' => 'Ukuran foto bukti lapangan maksimal 5 MB per berkas.',
            'surat_kepala_desa.mimes' => 'Surat pengantar Kepala Desa harus berupa dokumen PDF atau JPG/PNG.',
            'surat_kepala_desa.max' => 'Ukuran surat pengantar Kepala Desa maksimal 5 MB.',
        ];

        $validated = $request->validate($rules, $messages);

        $simulasi = $pengajuan->simulasi ?: $desa->latestSimulasi;

        if ($action === 'kirim') {
            // Validasi kelayakan
            if ($simulasi && $simulasi->status_kelayakan === 'Aman') {
                return back()->withInput()->withErrors([
                    'kelayakan' => 'Pengajuan ditolak. Status kelayakan desa adalah Aman. Pengajuan bantuan hanya dapat dikirim jika berstatus Kritis atau Waspada.',
                ]);
            }

            // Validasi tiket aktif
            if ($desa->hasActiveTicket()) {
                $aktif = $desa->activeTicket();
                return back()->withInput()->withErrors([
                    'tiket_aktif' => 'Sistem menolak pengajuan baru. Desa masih memiliki tiket aktif (' . ($aktif->nomor_tiket ?? 'Aktif') . ').',
                ]);
            }

            // Validasi foto (apakah ada berkas lama atau berkas baru)
            $existingFotoCount = $pengajuan->fotoKondisi()->count();
            $newFotoCount = $request->hasFile('foto_lapangan') ? count($request->file('foto_lapangan')) : 0;
            if ($existingFotoCount + $newFotoCount === 0) {
                return back()->withInput()->withErrors([
                    'foto_lapangan' => 'Foto bukti kondisi lapangan wajib diunggah minimal 1 foto sebelum dikirim.',
                ]);
            }
        }

        return DB::transaction(function () use ($pengajuan, $desa, $simulasi, $validated, $request, $action) {
            // Data kebutuhan terkunci dari Cek Kelayakan (tidak dapat diubah di form draf)
            $jumlahKk = $simulasi ? $simulasi->kk_terdampak : ($pengajuan->jumlah_kk ?? $validated['jumlah_kk']);
            $hariTanpaAir = $simulasi ? $simulasi->hari_tanpa_air : ($pengajuan->hari_tanpa_air ?? $validated['hari_tanpa_air']);
            $jarakSumberAir = $simulasi ? $simulasi->jarak_sumber_air : ($pengajuan->jarak_sumber_air ?? $validated['jarak_sumber_air']);

            $updateData = [
                'jumlah_kk' => $jumlahKk,
                'hari_tanpa_air' => $hariTanpaAir,
                'jarak_sumber_air' => $jarakSumberAir,
                'alasan_pengajuan' => $validated['alasan_pengajuan'],
                'lokasi_pengantaran' => $validated['lokasi_pengantaran'] ?? $pengajuan->lokasi_pengantaran,
                'latitude' => $validated['latitude'] ?? $pengajuan->latitude,
                'longitude' => $validated['longitude'] ?? $pengajuan->longitude,
                'catatan_tambahan' => $validated['catatan_tambahan'] ?? null,
            ];

            if ($action === 'kirim') {
                $updateData['status'] = 'Diajukan';
                $updateData['nomor_tiket'] = Pengajuan::generateNomorTiket();
                $updateData['tanggal_pengajuan'] = Carbon::now();
            }

            $pengajuan->update($updateData);

            // Simpan foto tambahan jika ada (maksimal total 3 foto)
            if ($request->hasFile('foto_lapangan')) {
                $currentCount = $pengajuan->fotoKondisi()->count();
                foreach ($request->file('foto_lapangan') as $foto) {
                    if ($currentCount >= 3) break;
                    $path = $foto->store('pengajuan/foto', 'public');
                    BerkasPengajuan::create([
                        'pengajuan_id' => $pengajuan->id,
                        'jenis_berkas' => 'foto_kondisi',
                        'nama_file' => $foto->getClientOriginalName(),
                        'path_file' => $path,
                        'mime_type' => $foto->getMimeType() ?: 'image/jpeg',
                        'ukuran_file' => $foto->getSize(),
                        'keterangan' => 'Foto bukti kondisi lapangan #' . ($currentCount + 1),
                    ]);
                    $currentCount++;
                }
            }

            // Update surat pengantar Kepala Desa jika diunggah baru
            if ($request->hasFile('surat_kepala_desa')) {
                // Hapus surat lama jika ada
                if ($oldSurat = $pengajuan->suratKepalaDesa) {
                    Storage::disk('public')->delete($oldSurat->path_file);
                    $oldSurat->delete();
                }

                $surat = $request->file('surat_kepala_desa');
                $path = $surat->store('pengajuan/surat', 'public');
                BerkasPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'jenis_berkas' => 'surat_kepala_desa',
                    'nama_file' => $surat->getClientOriginalName(),
                    'path_file' => $path,
                    'mime_type' => $surat->getMimeType() ?: 'application/pdf',
                    'ukuran_file' => $surat->getSize(),
                    'keterangan' => 'Surat Pengantar Resmi Kepala Desa berstempel basah',
                ]);
            }

            if ($action === 'kirim') {
                return redirect()->route('pengajuan.success', $pengajuan->id)
                    ->with('success', 'Draf berhasil dikirim ke antrean verifikasi Admin BPBD.');
            }

            return redirect()->route('pengajuan.show', $pengajuan->id)
                ->with('success', 'Draf pengajuan berhasil diperbarui.');
        });
    }

    /**
     * PBI-008: Menghapus draf pengajuan
     * Acceptance Criteria:
     * - Draf yang sudah dikirim tidak dapat dihapus
     * - Perangkat desa dapat menghapus draf sebelum dikirim
     */
    public function destroy(Pengajuan $pengajuan): RedirectResponse
    {
        Gate::authorize('delete', $pengajuan);

        DB::transaction(function () use ($pengajuan) {
            foreach ($pengajuan->berkas as $berkas) {
                Storage::disk('public')->delete($berkas->path_file);
                $berkas->delete();
            }
            $pengajuan->delete();
        });

        return redirect()->route('pengajuan.index')
            ->with('success', 'Draf pengajuan berhasil dihapus.');
    }

    /**
     * PBI-009: Mengirim draf yang tersimpan ke antrean verifikasi admin
     * Acceptance Criteria:
     * - Sistem menolak pengajuan baru jika desa sudah punya tiket aktif
     * - Sistem menerbitkan nomor tiket unik, status Diajukan, dan menempatkan di antrean Admin
     * - Memvalidasi hasil cek berstatus Kritis/Waspada sebelum dapat dikirim
     */
    public function submit(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        Gate::authorize('submit', $pengajuan);

        $desa = $pengajuan->desa;
        $simulasi = $pengajuan->simulasi ?: $desa->latestSimulasi;

        // 1. Validasi hasil cek kelayakan berstatus Kritis atau Waspada
        if ($simulasi && $simulasi->status_kelayakan === 'Aman') {
            return back()->withErrors([
                'kelayakan' => 'Pengajuan ditolak. Status kelayakan desa adalah Aman. Hanya status Kritis atau Waspada yang dapat diajukan.',
            ]);
        }

        // 2. Acceptance Criteria: Tolak jika desa sudah punya tiket aktif
        if ($desa->hasActiveTicket()) {
            $aktif = $desa->activeTicket();
            return back()->withErrors([
                'tiket_aktif' => 'Sistem menolak pengajuan baru. Desa ' . $desa->nama_desa . ' sudah memiliki tiket aktif (' . ($aktif->nomor_tiket ?? 'Aktif') . ').',
            ]);
        }

        // 3. Validasi foto kondisi minimal 1 foto
        if ($pengajuan->fotoKondisi()->count() === 0) {
            return back()->withErrors([
                'foto_lapangan' => 'Foto bukti kondisi lapangan wajib diunggah minimal 1 foto sebelum pengajuan dikirim.',
            ]);
        }

        // 4. Terbitkan nomor tiket unik dan ubah status ke Diajukan
        $nomorTiket = Pengajuan::generateNomorTiket();

        $pengajuan->update([
            'nomor_tiket' => $nomorTiket,
            'status' => 'Diajukan',
            'tanggal_pengajuan' => Carbon::now(),
        ]);

        return redirect()->route('pengajuan.success', $pengajuan->id)
            ->with('success', 'Pengajuan berhasil dikirim dengan Nomor Tiket: ' . $nomorTiket);
    }

    /**
     * PBI-009: Halaman "Pengajuan Berhasil Dikirim" (Mockup Halaman 60)
     */
    public function success(Pengajuan $pengajuan): View|RedirectResponse
    {
        Gate::authorize('view', $pengajuan);

        // Jika masih draf, arahkan kembali ke detail draf
        if ($pengajuan->isDraft()) {
            return redirect()->route('pengajuan.show', $pengajuan->id);
        }

        $pengajuan->load(['desa', 'simulasi', 'berkas']);
        $desa = $pengajuan->desa;

        return view('pengajuan.success', compact('pengajuan', 'desa'));
    }

    /**
     * Hapus satu berkas lampiran dari draf
     */
    public function destroyBerkas(BerkasPengajuan $berkas): JsonResponse|RedirectResponse
    {
        $pengajuan = $berkas->pengajuan;
        Gate::authorize('update', $pengajuan);

        Storage::disk('public')->delete($berkas->path_file);
        $berkas->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Berkas berhasil dihapus.']);
        }

        return back()->with('success', 'Berkas berhasil dihapus dari draf.');
    }
}

