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
     * Dapatkan desa aktif untuk simulasi (mendukung mode tanpa login)
     */
    public function getActiveDesa(): Desa
    {
        // 1. Jika pengguna sedang login dan terhubung dengan data desa
        if (Auth::check() && Auth::user()->desa) {
            return Auth::user()->desa;
        }

        // 2. Jika ada kode desa di session simulasi
        $kodeDesa = session('simulasi_desa_kode');
        if ($kodeDesa) {
            $desa = Desa::where('kode_desa', $kodeDesa)->first();
            if ($desa) {
                return $desa;
            }
        }

        // 3. Default ke Desa Serang (Simulasi Waspada) atau Desa Tanalum (Kritis)
        $defaultDesa = Desa::where('nama_desa', 'like', '%Serang%')->first()
            ?: (Desa::where('nama_desa', 'like', '%Tanalum%')->first() ?: Desa::first());

        if (!$defaultDesa) {
            $defaultDesa = Desa::create([
                'kode_desa' => '33.03.10.2001',
                'nama_desa' => 'Desa Serang',
                'kecamatan' => 'Karangreja',
                'jumlah_kk_total' => 750,
                'jumlah_jiwa_total' => 2800,
                'jumlah_balita' => 110,
                'jumlah_lansia' => 90,
                'jumlah_disabilitas' => 15,
                'titik_drop_air' => 'Posko Mandiri RT 01 RW 04 Desa Serang',
                'nama_pj_darurat' => 'Agus Hendra',
                'no_wa_darurat' => '081398765432',
                'latitude' => -7.244225,
                'longitude' => 109.279768,
            ]);

            SimulasiKelayakan::firstOrCreate(
                ['kode_desa' => $defaultDesa->kode_desa, 'status_kelayakan' => 'Waspada'],
                [
                    'kk_terdampak' => 85,
                    'jiwa_rentan' => 25,
                    'hari_tanpa_air' => 8,
                    'jarak_sumber_air' => 800,
                    'skor_ahp_saw' => 74.80,
                    'rekomendasi' => 'Penuhi syarat administrasi dan siapkan titik tandon untuk pengajuan bantuan pasokan.',
                ]
            );
        }

        session(['simulasi_desa_kode' => $defaultDesa->kode_desa, 'simulasi_mode' => 'waspada']);
        return $defaultDesa;
    }

    /**
     * Beralih mode simulasi status kelayakan (Aman / Waspada / Kritis) tanpa perlu login
     */
    public function switchSimulasi(string $mode): RedirectResponse
    {
        $mode = strtolower($mode);

        if ($mode === 'aman') {
            $desa = Desa::where('nama_desa', 'like', '%Bodas%')->first()
                ?: (Desa::where('kecamatan', 'Karangjambu')->first()
                    ?: Desa::firstOrCreate(
                        ['kode_desa' => '33.03.17.2001'],
                        ['nama_desa' => 'Desa Bodas', 'kecamatan' => 'Karangjambu', 'jumlah_kk_total' => 400]
                    ));

            // Pastikan memiliki simulasi Aman
            SimulasiKelayakan::updateOrCreate(
                ['kode_desa' => $desa->kode_desa],
                [
                    'status_kelayakan' => 'Aman',
                    'skor_ahp_saw' => 48.10,
                    'kk_terdampak' => 20,
                    'jiwa_rentan' => 5,
                    'hari_tanpa_air' => 2,
                    'jarak_sumber_air' => 200,
                    'rekomendasi' => 'Kondisi pasokan air masih terkendali secara mandiri di tingkat lingkungan.',
                ]
            );

            session(['simulasi_desa_kode' => $desa->kode_desa, 'simulasi_mode' => 'aman']);

            return redirect()->route('pengajuan.index')
                ->with('info', 'Beralih ke Simulasi Status AMAN (' . $desa->nama_desa . '). Coba klik tombol "Buat Pengajuan Baru" untuk melihat pencegahan pop-up penolakan!');
        }

        if ($mode === 'kritis') {
            $desa = Desa::where('nama_desa', 'like', '%Tanalum%')->first()
                ?: Desa::first();

            if ($desa) {
                SimulasiKelayakan::updateOrCreate(
                    ['kode_desa' => $desa->kode_desa],
                    [
                        'status_kelayakan' => 'Kritis',
                        'skor_ahp_saw' => 92.40,
                        'kk_terdampak' => 120,
                        'jiwa_rentan' => 40,
                        'hari_tanpa_air' => 14,
                        'jarak_sumber_air' => 1200,
                        'rekomendasi' => 'Segera ajukan pasokan air bersih darurat untuk kebutuhan warga terdampak.',
                    ]
                );
                session(['simulasi_desa_kode' => $desa->kode_desa, 'simulasi_mode' => 'kritis']);
            }

            return redirect()->route('pengajuan.index')
                ->with('info', 'Beralih ke Simulasi Status KRITIS (' . ($desa?->nama_desa ?? 'Desa Tanalum') . ').');
        }

        // Default: Waspada (Desa Serang)
        $desa = Desa::where('nama_desa', 'like', '%Serang%')->first()
            ?: Desa::first();

        if ($desa) {
            SimulasiKelayakan::updateOrCreate(
                ['kode_desa' => $desa->kode_desa],
                [
                    'status_kelayakan' => 'Waspada',
                    'skor_ahp_saw' => 74.80,
                    'kk_terdampak' => 85,
                    'jiwa_rentan' => 25,
                    'hari_tanpa_air' => 8,
                    'jarak_sumber_air' => 800,
                    'rekomendasi' => 'Penuhi syarat administrasi dan siapkan titik tandon untuk pengajuan bantuan pasokan.',
                ]
            );
            session(['simulasi_desa_kode' => $desa->kode_desa, 'simulasi_mode' => 'waspada']);
        }

        return redirect()->route('pengajuan.index')
            ->with('info', 'Beralih ke Simulasi Status WASPADA (' . ($desa?->nama_desa ?? 'Desa Serang') . ').');
    }

    /**
     * PBI-008: Menampilkan daftar draf pengajuan dan status permohonan desa
     * Acceptance Criteria:
     * - Draf desa lain tidak terlihat
     * - Perangkat desa dapat melihat draf milik desanya
     */
    public function index(Request $request): View
    {
        $desa = $this->getActiveDesa();

        // Ambil hanya draf milik desa aktif
        $drafList = Pengajuan::where('kode_desa', $desa->kode_desa)
            ->where('status', 'Draf')
            ->with(['simulasi', 'berkas'])
            ->latest()
            ->paginate(10, ['*'], 'draf_page');

        // Ambil riwayat pengajuan yang sudah dikirim (non-draf)
        $riwayatList = Pengajuan::where('kode_desa', $desa->kode_desa)
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
    public function create(Request $request): View|RedirectResponse
    {
        $desa = $this->getActiveDesa();

        // Ambil hasil simulasi kelayakan (bisa via id atau yang terbaru)
        $simulasi = null;
        if ($request->has('simulasi_id')) {
            $simulasi = SimulasiKelayakan::where('kode_desa', $desa->kode_desa)
                ->where('id', $request->query('simulasi_id'))
                ->first();
        }

        if (!$simulasi) {
            $simulasi = $desa->latestSimulasi;
        }

        // Revisi 1: Jika hasil cek kelayakan berstatus 'Aman', tolak akses ke halaman pengajuan dan tampilkan pop-up modal
        if ($simulasi && $simulasi->status_kelayakan === 'Aman') {
            return redirect()->route('pengajuan.index')->with('modal_kelayakan_aman', [
                'desa' => $desa->nama_desa,
                'skor' => $simulasi->skor_ahp_saw,
                'status' => $simulasi->status_kelayakan,
                'rekomendasi' => $simulasi->rekomendasi ?? 'Kondisi air desa masih dalam batas aman terkendali.',
                'pesan' => 'Hasil Cek Kelayakan Mandiri desa Anda saat ini berstatus AMAN (Skor SPK: ' . number_format($simulasi->skor_ahp_saw, 1) . ' / 100). Sesuai ketentuan SOP Penanggulangan Darurat Air BPBD, armada tangki bantuan hanya diprioritaskan untuk desa dengan status Kritis atau Waspada. Anda tidak dapat mengakses formulir pengajuan bantuan.',
            ]);
        }

        // Jika belum ada simulasi sama sekali, sediakan instance default agar form tetap berfungsi
        if (!$simulasi) {
            $simulasi = new SimulasiKelayakan([
                'kode_desa' => $desa->kode_desa,
                'kk_terdampak' => 85,
                'jiwa_rentan' => ($desa->jumlah_balita + $desa->jumlah_lansia + $desa->jumlah_disabilitas),
                'hari_tanpa_air' => 8,
                'jarak_sumber_air' => 800,
                'skor_ahp_saw' => 74.80,
                'status_kelayakan' => 'Waspada',
                'rekomendasi' => 'Penuhi syarat administrasi dan siapkan titik tandon untuk pengajuan bantuan pasokan.',
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
        $desa = $this->getActiveDesa();
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
            $simulasi = SimulasiKelayakan::where('kode_desa', $desa->kode_desa)->find($validated['simulasi_id']);
        }
        if (!$simulasi) {
            $simulasi = $desa->latestSimulasi;
        }

        // Revisi 1: Jika status kelayakan desa adalah 'Aman', tolak akses dan tampilkan pop-up modal
        if ($simulasi && $simulasi->status_kelayakan === 'Aman') {
            return redirect()->route('pengajuan.index')->with('modal_kelayakan_aman', [
                'desa' => $desa->nama_desa,
                'skor' => $simulasi->skor_ahp_saw,
                'status' => $simulasi->status_kelayakan,
                'rekomendasi' => $simulasi->rekomendasi ?? 'Kondisi air desa masih dalam batas aman terkendali.',
                'pesan' => 'Pengajuan ditolak. Berdasarkan hasil Cek Kelayakan Mandiri (F-02), desa Anda berstatus AMAN. Pengajuan bantuan air bersih hanya dapat diproses untuk status Kritis atau Waspada.',
            ]);
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
                'kode_desa' => $desa->kode_desa,
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
        $desa = $pengajuan->desa ?: $this->getActiveDesa();

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
        $desa = $pengajuan->desa ?: $this->getActiveDesa();
        $simulasi = $pengajuan->simulasi ?: $desa->latestSimulasi;

        return view('pengajuan.edit', compact('pengajuan', 'desa', 'simulasi'));
    }

    /**
     * PBI-008: Memperbarui isi draf pengajuan
     */
    public function update(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        Gate::authorize('update', $pengajuan);

        $desa = $pengajuan->desa ?: $this->getActiveDesa();
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
                        'keterangan' => 'Foto bukti kondisi lapangan tambahan',
                    ]);
                    $currentCount++;
                }
            }

            // Ganti surat pengantar Kepala Desa jika diunggah baru
            if ($request->hasFile('surat_kepala_desa')) {
                if ($pengajuan->suratKepalaDesa) {
                    Storage::disk('public')->delete($pengajuan->suratKepalaDesa->path_file);
                    $pengajuan->suratKepalaDesa->delete();
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

        $desa = $pengajuan->desa ?: $this->getActiveDesa();
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

        $pengajuan->load(['desa', 'simulasi', 'berkas']);
        $desa = $pengajuan->desa ?: $this->getActiveDesa();

        return view('pengajuan.success', compact('pengajuan', 'desa'));
    }

    /**
     * Hapus berkas lampiran secara individual via AJAX
     */
    public function destroyBerkas(BerkasPengajuan $berkas): JsonResponse
    {
        $pengajuan = $berkas->pengajuan;
        Gate::authorize('update', $pengajuan);

        Storage::disk('public')->delete($berkas->path_file);
        $berkas->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berkas berhasil dihapus.',
        ]);
    }
}
