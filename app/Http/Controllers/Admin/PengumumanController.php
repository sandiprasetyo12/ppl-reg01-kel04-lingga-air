<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PengumumanRequest;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    /**
     * Tampilkan daftar pengumuman untuk Admin BPBD.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $query = Pengumuman::with('creator')->orderByDesc('published_at');

        if ($status && in_array($status, ['kritis', 'waspada', 'aman'], true)) {
            $query->where('tingkat_kelayakan', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        $pengumumans = $query->paginate(10)->withQueryString();
        
        return view('admin.pengumuman.index', compact('pengumumans', 'status', 'search'));
    }

    /**
     * Form buat pengumuman baru.
     */
    public function create(): View
    {
        return view('admin.pengumuman.create');
    }

    /**
     * Simpan pengumuman baru ke database.
     */
    public function store(PengumumanRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if (! isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        Pengumuman::create([
            ...$validated,
            'created_by' => $request->user()->id,
            'published_at' => now(),
        ]);

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman kedaruratan berhasil diterbitkan.');
    }

    /**
     * Form ubah pengumuman.
     */
    public function edit(Pengumuman $pengumuman): View
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Perbarui pengumuman di database.
     */
    public function update(
        PengumumanRequest $request,
        Pengumuman $pengumuman
    ): RedirectResponse {
        $validated = $request->validated();
        if (! isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $pengumuman->update($validated);

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Toggle status aktif / nonaktif pengumuman.
     */
    public function toggleAktif(Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->update([
            'is_active' => ! $pengumuman->is_active,
        ]);

        $statusMsg = $pengumuman->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status pengumuman berhasil {$statusMsg}.");
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->delete();

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
