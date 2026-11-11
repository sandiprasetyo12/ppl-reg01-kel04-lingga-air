@extends('layouts.dashboard')

@section('title', 'Dashboard Admin BPBD - LinggaAir')
@section('page-title', 'Dashboard Admin BPBD')

@section('content')
<div class="space-y-6">
    <section class="bg-white border border-[#e3e3e0] rounded-lg p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <h2 class="text-lg font-semibold text-[#1b1b18]">
                Selamat Datang, {{ auth()->user()->username }}
            </h2>
            <p class="text-sm text-[#52525b]">
                Anda masuk sebagai Admin BPBD. Gunakan menu di bawah ini untuk mengelola master data desa.
            </p>
        </div>

        <a
            href="{{ route('admin.desa.index') }}"
            class="min-h-[44px] inline-flex items-center justify-center px-5 py-2.5 bg-[#1b1b18] hover:bg-black text-white text-sm font-medium rounded-md transition shrink-0"
        >
            Kelola Master Data Desa
        </a>
    </section>
</div>
@endsection
