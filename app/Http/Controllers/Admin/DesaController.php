<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    // Tampilkan daftar semua desa dengan search & filter kecamatan
    public function index(Request $request)
    {
        $query = Desa::query();

        // Filter pencarian berdasarkan nama desa atau kode desa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_desa', 'like', "%{$search}%")
                  ->orWhere('kode_desa', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Daftar kecamatan unik untuk dropdown filter
        $kecamatans = Desa::select('kecamatan')->distinct()->orderBy('kecamatan')->pluck('kecamatan');

        // Pagination 20 per halaman
        $desas = $query->orderBy('kecamatan')->orderBy('nama_desa')->paginate(20)->withQueryString();

        return view('admin.desa.index', compact('desas', 'kecamatans'));
    }

    // Tampilkan detail satu desa
    public function show(string $kode_desa)
    {
        $desa = Desa::where('kode_desa', $kode_desa)->with('user')->firstOrFail();
        return view('admin.desa.show', compact('desa'));
    }

    // Tampilkan form edit desa
    public function edit(string $kode_desa)
    {
        $desa = Desa::where('kode_desa', $kode_desa)->firstOrFail();
        return view('admin.desa.edit', compact('desa'));
    }

    // Proses update data desa
    public function update(Request $request, string $kode_desa)
    {
        $desa = Desa::where('kode_desa', $kode_desa)->firstOrFail();

        $validated = $request->validate([
            'jumlah_kk_total' => 'nullable|integer|min:0',
            'jumlah_jiwa_total' => 'nullable|integer|min:0',
            'jumlah_balita' => 'nullable|integer|min:0',
            'jumlah_lansia' => 'nullable|integer|min:0',
            'jumlah_disabilitas' => 'nullable|integer|min:0',
            'nama_pj_darurat' => 'nullable|string|max:100',
            'no_wa_darurat' => 'nullable|string|max:20',
            'titik_drop_air' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $desa->update($validated);

        return redirect()->route('admin.desa.index')->with('success', "Data desa {$desa->nama_desa} berhasil diperbarui.");
    }
}