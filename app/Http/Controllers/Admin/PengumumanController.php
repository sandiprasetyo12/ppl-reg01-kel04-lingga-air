<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PengumumanRequest;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengumumans = Pengumuman::with('creator')
            ->orderByDesc('published_at')
            ->paginate(15);
        
        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return  view('admin.pengumuman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengumumanRequest $request): RedirectResponse
    {
        Pengumuman::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
            'published_at' => now(),
        ]);

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Pengumuman $pengumuman): View
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(
        PengumumanRequest $request,
        Pengumuman $pengumuman
    ): RedirectResponse {
        $pengumuman->update($request->validated());

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function toggleAktif(Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->update([
            'is_active' => ! $pengumuman->is_active,
        ]);

        return back()->with('success', 'Status pengumuman berhasil diperbarui.');
    }
}
