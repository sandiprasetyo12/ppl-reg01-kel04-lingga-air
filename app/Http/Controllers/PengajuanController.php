<?php

namespace App\Http\Controllers;

use App\Models\BerkasPengajuan;
use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\RiwayatCekKelayakan;
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
     * Dapatkan desa aktif untuk pengajuan bantuan air bersih
     */
    public function getActiveDesa(?Request $request = null): Desa
    {
        // 1. Jika pengguna login dan memiliki akun desa
        if (Auth::check() && Auth::user()->desa) {
            return Auth::user()->desa;
        }

        // 2. Berdasarkan query param kode_desa
        if ($request && $request->filled('kode_desa')) {
            $desa = Desa::where('kode_desa', $request->query('kode_desa'))->first();
            if ($desa) {
                session(['active_desa_kode' => $desa->kode_desa]);
                return $desa;
            }
        }

        // 3. Berdasarkan riwayat_id jika dikirim dari hasil cek kelayakan
        if ($request && $request->filled('riwayat_id')) {
            $riwayat = RiwayatCekKelayakan::find($request->query('riwayat_id'));
            if ($riwayat && $riwayat->desa) {
                session(['active_desa_kode' => $riwayat->desa->kode_desa]);
                return $riwayat->desa;
            }
        }

        // 4. Berdasarkan session active_desa_kode
        $sessionKode = session('active_desa_kode');
        if ($sessionKode) {
            $desa = Desa::where('kode_desa', $sessionKode)->first();
            if ($desa) {
                return $desa;
            }
        }

        // 5. Default ke desa yang memiliki riwayat cek kelayakan terbaru atau Desa Serang
        $defaultDesa = Desa::whereHas('riwayatCekKelayakan')->first()
            ?: (Desa::where('nama_desa', 'like', '%Serang%')->first() ?: Desa::first());

        if (!$defaultDesa) {
            $defaultDesa = Desa::firstOrCreate(
                ['kode_desa' => '33.03.10.2001'],
                [
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
                ]
            );
        }

        session(['active_desa_kode' => $defaultDesa->kode_desa]);
        return $defaultDesa;
    }

    /**
     * PBI-008: Menampilkan daftar draf pengajuan dan status permohonan desa
     */
    public function index(Request $request): View
    {
        $desa = $this->getActiveDesa($request);

        $drafList = Pengajuan::where('kode_desa', $desa->kode_desa)
            ->where('status', 'Draf')
            ->with(['riwayatKelayakan', 'berkas'])
            ->latest()
            ->paginate(10, ['*'], 'draf_page');

        $riwayatList = Pengajuan::where('kode_desa', $desa->kode_desa)
            ->where('status', '!=', 'Draf')
            ->with(['riwayatKelayakan', 'berkas'])
            ->latest()
            ->paginate(10, ['*'], 'riwayat_page');

        $activeTicket = $desa->activeTicket();
        $hasActiveTicket = $desa->hasActiveTicket();

        return view('pengajuan.index', compact('desa', 'drafList', 'riwayatList', 'activeTicket', 'hasActiveTicket'));
    }

    /**
     * PBI-007: Menampilkan formulir penyusunan pengajuan baru
     * Terisi otomatis dari hasil Cek Kelayakan Mandiri (F-02)
     */
    public function create(Request $request): View|RedirectResponse
    {
        $desa = $this->getActiveDesa($request);

        // Ambil riwayat cek kelayakan (dari query riwayat_id atau yang terbaru dari desa ini)
        $riwayat = null;
        if ($request->filled('riwayat_id')) {
            $riwayat = RiwayatCekKelayakan::where('kode_desa', $desa->kode_desa)
                ->where('id', $request->query('riwayat_id'))
                ->first();
        }

        if (!$riwayat) {
            $riwayat = $desa->latestRiwayatKelayakan;
        }

        // Jika desa belum memiliki riwayat cek kelayakan, arahkan ke Cek Kelayakan (F-02)
        if (!$riwayat) {
            return redirect()->route('cek-kelayakan.index')
                ->with('info', 'Silakan jalankan Cek Kelayakan Mandiri terlebih dahulu untuk Desa ' . $desa->nama_desa . ' sebelum menyusun pengajuan bantuan air.');
        }

        // Jika hasil cek kelayakan berstatus Aman, tolak akses dan tampilkan modal pop-up
        if (strtolower($riwayat->status) === 'aman') {
            return redirect()->route('pengajuan.index')->with('modal_kelayakan_aman', [
                'desa' => $desa->nama_desa,
                'skor' => $riwayat->skor,
                'status' => 'AMAN',
                'rekomendasi' => $riwayat->rekomendasi ?? 'Kondisi air desa masih dalam batas aman terkendali.',
                'pesan' => 'Hasil Cek Kelayakan Mandiri desa Anda berstatus AMAN (Skor SPK: ' . number_format($riwayat->skor, 1) . ' / 100). Sesuai ketentuan SOP Penanggulangan Darurat Air BPBD, armada tangki bantuan hanya diprioritaskan untuk desa berstatus Kritis atau Waspada. Anda tidak dapat mengakses formulir pengajuan bantuan.',
            ]);
        }

        $hasActiveTicket = $desa->hasActiveTicket();
        $activeTicket = $desa->activeTicket();

        // Variabel $simulasi disediakan sebagai alias $riwayat demi kompatibilitas blade
        $simulasi = $riwayat;

        return view('pengajuan.create', compact('desa', 'riwayat', 'simulasi', 'hasActiveTicket', 'activeTicket'));
    }

    /**
     * PBI-007 & PBI-009: Menyimpan pengajuan sebagai draf atau langsung mengirim ke antrean
     */
    public function store(Request $request): RedirectResponse
    {
        $desa = $this->getActiveDesa($request);
        $action = $request->input('action', 'draf');

        $rules = [
            'riwayat_id' => ['nullable', 'exists:riwayat_cek_kelayakan,id'],
            'simulasi_id' => ['nullable'],
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

        $riwayatId = $validated['riwayat_id'] ?? ($validated['simulasi_id'] ?? null);
        $riwayat = null;
        if ($riwayatId) {
            $riwayat = RiwayatCekKelayakan::where('kode_desa', $desa->kode_desa)->find($riwayatId);
        }
        if (!$riwayat) {
            $riwayat = $desa->latestRiwayatKelayakan;
        }

        if ($riwayat && strtolower($riwayat->status) === 'aman') {
            return redirect()->route('pengajuan.index')->with('modal_kelayakan_aman', [
                'desa' => $desa->nama_desa,
                'skor' => $riwayat->skor,
                'status' => 'AMAN',
                'rekomendasi' => $riwayat->rekomendasi ?? 'Kondisi air desa masih dalam batas aman terkendali.',
                'pesan' => 'Pengajuan ditolak. Berdasarkan hasil Cek Kelayakan Mandiri (F-02), desa Anda berstatus AMAN. Pengajuan bantuan air bersih hanya dapat diproses untuk status Kritis atau Waspada.',
            ]);
        }

        if ($action === 'kirim') {
            if ($riwayat && strtolower($riwayat->status) === 'aman') {
                return back()->withInput()->withErrors([
                    'kelayakan' => 'Pengajuan ditolak. Status kelayakan desa adalah Aman. Pengajuan bantuan hanya dapat dikirim jika berstatus Kritis atau Waspada.',
                ]);
            }

            if ($desa->hasActiveTicket()) {
                $aktif = $desa->activeTicket();
                return back()->withInput()->withErrors([
                    'tiket_aktif' => 'Sistem menolak pengajuan baru. Desa ' . $desa->nama_desa . ' saat ini masih memiliki tiket aktif (' . ($aktif->nomor_tiket ?? 'Tiket Aktif') . ') dengan status: ' . $aktif->status . '.',
                ]);
            }

            if (!$request->hasFile('foto_lapangan') && empty($validated['foto_lapangan'])) {
                return back()->withInput()->withErrors([
                    'foto_lapangan' => 'Foto bukti kondisi lapangan wajib diunggah minimal 1 foto untuk pengiriman pengajuan.',
                ]);
            }
        }

        return DB::transaction(function () use ($desa, $riwayat, $validated, $request, $action) {
            $status = ($action === 'kirim') ? 'Diajukan' : 'Draf';
            $nomorTiket = ($action === 'kirim') ? Pengajuan::generateNomorTiket() : null;
            $tanggalPengajuan = ($action === 'kirim') ? Carbon::now() : null;

            // Parameter kebutuhan terkunci dari hasil perhitungan Cek Kelayakan Mandiri
            $jumlahKk = $riwayat ? $riwayat->kk_terdampak : $validated['jumlah_kk'];
            $hariTanpaAir = $riwayat ? $riwayat->hari_tanpa_air : $validated['hari_tanpa_air'];
            $jarakSumberAir = $riwayat ? $riwayat->jarak_sumber_meter : $validated['jarak_sumber_air'];

            $pengajuan = Pengajuan::create([
                'nomor_tiket' => $nomorTiket,
                'kode_desa' => $desa->kode_desa,
                'riwayat_cek_kelayakan_id' => $riwayat?->id,
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
     */
    public function show(Pengajuan $pengajuan): View
    {
        Gate::authorize('view', $pengajuan);

        $pengajuan->load(['desa', 'riwayatKelayakan', 'berkas']);
        $desa = $pengajuan->desa ?: $this->getActiveDesa();

        return view('pengajuan.show', compact('pengajuan', 'desa'));
    }

    /**
     * PBI-008: Menampilkan formulir ubah draf
     */
    public function edit(Pengajuan $pengajuan): View
    {
        Gate::authorize('update', $pengajuan);

        $pengajuan->load(['desa', 'riwayatKelayakan', 'berkas']);
        $desa = $pengajuan->desa ?: $this->getActiveDesa();
        $simulasi = $pengajuan->riwayatKelayakan ?: $desa->latestRiwayatKelayakan;

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
        $riwayat = $pengajuan->riwayatKelayakan ?: $desa->latestRiwayatKelayakan;

        if ($action === 'kirim') {
            if ($riwayat && strtolower($riwayat->status) === 'aman') {
                return back()->withInput()->withErrors([
                    'kelayakan' => 'Pengajuan ditolak. Status kelayakan desa adalah Aman. Pengajuan bantuan hanya dapat dikirim jika berstatus Kritis atau Waspada.',
                ]);
            }

            if ($desa->hasActiveTicket()) {
                $aktif = $desa->activeTicket();
                return back()->withInput()->withErrors([
                    'tiket_aktif' => 'Sistem menolak pengajuan baru. Desa masih memiliki tiket aktif (' . ($aktif->nomor_tiket ?? 'Aktif') . ').',
                ]);
            }

            $existingFotoCount = $pengajuan->fotoKondisi()->count();
            $newFotoCount = $request->hasFile('foto_lapangan') ? count($request->file('foto_lapangan')) : 0;
            if ($existingFotoCount + $newFotoCount === 0) {
                return back()->withInput()->withErrors([
                    'foto_lapangan' => 'Foto bukti kondisi lapangan wajib diunggah minimal 1 foto sebelum dikirim.',
                ]);
            }
        }

        return DB::transaction(function () use ($pengajuan, $desa, $riwayat, $validated, $request, $action) {
            $jumlahKk = $riwayat ? $riwayat->kk_terdampak : ($pengajuan->jumlah_kk ?? $validated['jumlah_kk']);
            $hariTanpaAir = $riwayat ? $riwayat->hari_tanpa_air : ($pengajuan->hari_tanpa_air ?? $validated['hari_tanpa_air']);
            $jarakSumberAir = $riwayat ? $riwayat->jarak_sumber_meter : ($pengajuan->jarak_sumber_air ?? $validated['jarak_sumber_air']);

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
     */
    public function submit(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        Gate::authorize('submit', $pengajuan);

        $desa = $pengajuan->desa ?: $this->getActiveDesa();
        $riwayat = $pengajuan->riwayatKelayakan ?: $desa->latestRiwayatKelayakan;

        if ($riwayat && strtolower($riwayat->status) === 'aman') {
            return back()->withErrors([
                'kelayakan' => 'Pengajuan ditolak. Status kelayakan desa adalah Aman. Hanya status Kritis atau Waspada yang dapat diajukan.',
            ]);
        }

        if ($desa->hasActiveTicket()) {
            $aktif = $desa->activeTicket();
            return back()->withErrors([
                'tiket_aktif' => 'Sistem menolak pengajuan baru. Desa ' . $desa->nama_desa . ' sudah memiliki tiket aktif (' . ($aktif->nomor_tiket ?? 'Aktif') . ').',
            ]);
        }

        if ($pengajuan->fotoKondisi()->count() === 0) {
            return back()->withErrors([
                'foto_lapangan' => 'Foto bukti kondisi lapangan wajib diunggah minimal 1 foto sebelum pengajuan dikirim.',
            ]);
        }

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
     * PBI-009: Halaman "Pengajuan Berhasil Dikirim"
     */
    public function success(Pengajuan $pengajuan): View|RedirectResponse
    {
        Gate::authorize('view', $pengajuan);

        $pengajuan->load(['desa', 'riwayatKelayakan', 'berkas']);
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
