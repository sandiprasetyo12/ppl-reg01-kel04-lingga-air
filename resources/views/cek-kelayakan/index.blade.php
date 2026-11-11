<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Kelayakan Mandiri | Lingga Air</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            font-size: 15px;
            font-family: Poppins, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #202b3d;
            background: #f1f5f9;
        }
        * { box-sizing: border-box; }
        body { margin: 0; overflow-x: hidden; }
        .topbar { background: #086288; color: #fff; }
        .topbar-inner { position: relative; display: flex; align-items: center; justify-content: space-between; height: 76px; padding: 0 26px 0 26px; }
        .brand { display: flex; max-width: 204px; min-width: 0; align-items: center; gap: 9px; font-size: 1.08rem; font-weight: 750; letter-spacing: -.03em; line-height: 1.15; }
        .brand-mark { display: grid; flex: 0 0 36px; place-items: center; width: 36px; height: 36px; border: 1px solid rgb(255 255 255 / 28%); border-radius: 10px; background: rgb(255 255 255 / 12%); font-size: 1rem; }
        .brand > span:last-child { min-width: 0; }
        .brand small { display: block; max-width: 154px; margin-top: 4px; font-size: .5rem; font-weight: 600; letter-spacing: .025em; line-height: 1.2; overflow-wrap: anywhere; }
        .brand-wrap { display: flex; align-items: center; gap: 0; min-width: 0; }
        .topbar-label { position: absolute; left: 240px; display: flex; align-items: center; min-height: 24px; padding-left: 0; color: #e0f0f7; font-size: .9rem; line-height: 1.4; white-space: nowrap; }
        .account { display: flex; align-items: center; gap: 11px; margin-left: 20px; color: #fff; text-align: right; }
        .account-copy { max-width: 220px; overflow: hidden; font-size: .86rem; font-weight: 650; line-height: 1.3; text-overflow: ellipsis; white-space: nowrap; }
        .account-copy small { display: block; overflow: hidden; margin-top: 2px; color: #d7eaf1; font-size: .74rem; font-weight: 400; text-overflow: ellipsis; }
        .account-avatar { display: grid; flex: 0 0 40px; place-items: center; width: 40px; height: 40px; border: 1px solid rgb(255 255 255 / 48%); border-radius: 50%; background: rgb(255 255 255 / 12%); font-size: 1.1rem; }
        .app-frame { display: grid; grid-template-columns: minmax(0, 240px) minmax(0, 1fr); min-width: 0; min-height: calc(100vh - 76px); }
        .sidebar { display: flex; min-width: 0; flex-direction: column; padding: 24px 14px 18px; border-right: 1px solid #e0e8ef; background: #fff; }
        .sidebar-label { margin: 0 0 14px 12px; color: #8294a8; font-size: .76rem; font-weight: 750; letter-spacing: .07em; text-transform: uppercase; }
        .side-nav { display: grid; gap: 5px; }
        .side-link { display: flex; min-width: 0; align-items: center; gap: 10px; min-height: 46px; padding: 0 10px; border-radius: 9px; color: #41566c; font-size: .88rem; font-weight: 500; text-decoration: none; overflow-wrap: anywhere; }
        .side-link.active { background: #086288; color: #fff; font-weight: 700; }
        .side-link.disabled { color: #8393a6; cursor: not-allowed; }
        .side-icon { display: grid; flex: 0 0 26px; place-items: center; width: 26px; height: 26px; color: #829bb1; font-size: 1.1rem; font-weight: 600; }
        .side-link.active .side-icon { color: #fff; }
        .sidebar-help { margin-top: auto; padding: 17px 16px; border: 1px solid #cae4ef; border-radius: 12px; background: #f0f8fb; color: #344b61; }
        .sidebar-help-icon { margin-bottom: 8px; color: #086288; font-size: 1.25rem; }
        .sidebar-help strong { font-size: .88rem; }
        .sidebar-help p { margin: 6px 0 0; color: #60768b; font-size: .8rem; line-height: 1.5; }
        .sidebar-footer { margin: 16px 4px 0; color: #8294a8; font-size: .73rem; line-height: 1.55; }
        .main-content { min-width: 0; }
        .shell { max-width: 1440px; margin: 0 auto; padding: 24px 38px 64px; }
        .eyebrow { color: #0b6d8d; font-size: .76rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        h1 { margin: 8px 0 8px; font-size: clamp(1.9rem, 4vw, 2.5rem); letter-spacing: -.045em; line-height: 1.15; }
        .intro { max-width: 800px; margin: 0; color: #667991; font-size: 1.05rem; line-height: 1.6; }
        .layout { display: grid; grid-template-columns: minmax(0, 1fr); gap: 22px; margin-top: 24px; align-items: stretch; }
        .layout.has-result { grid-template-columns: minmax(0, 1fr); align-items: start; }
        .card { border: 1px solid #dce5ef; border-radius: 16px; background: #fff; box-shadow: 0 2px 5px rgb(31 48 70 / 5%); }
        .form-card { min-width: 0; padding: 26px 28px; }
        .has-result .form-card { padding: 22px 26px; }
        h2 { margin: 0; font-size: 1.2rem; }
        .section-copy { margin: 6px 0 18px; color: #73859c; font-size: .86rem; line-height: 1.5; }
        .section-title { margin: 20px 0 12px; padding-top: 17px; border-top: 1px solid #e8edf3; color: #34445b; font-size: .9rem; font-weight: 800; }
        .fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px 20px; }
        .has-result .fields { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px 18px; }
        .village-selects { display: grid; grid-template-columns: minmax(0, 1fr); gap: 16px; margin-bottom: 24px; }
        .village-field { min-width: 0; }
        select { width: 100%; min-height: 44px; padding: 9px 12px; border: 1px solid #d7e1ed; border-radius: 8px; background: #fff; color: #253348; font: inherit; font-size: .92rem; }
        select:focus { outline: 3px solid rgb(11 100 132 / 13%); border-color: #0b6484; }
        label { display: block; margin-bottom: 7px; color: #34445b; font-size: .88rem; font-weight: 700; }
        .has-result .field-wide { grid-column: span 2; }
        .has-result .field-wide label { white-space: nowrap; }
        .input-wrap { position: relative; }
        input { width: 100%; min-height: 44px; padding: 9px 58px 9px 12px; border: 1px solid #d7e1ed; border-radius: 8px; background: #fff; color: #253348; font: inherit; font-size: .92rem; }
        input[type="number"] { appearance: textfield; -moz-appearance: textfield; }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { margin: 0; -webkit-appearance: none; }
        input:focus { outline: 3px solid rgb(11 100 132 / 13%); border-color: #0b6484; }
        input:disabled { border-color: #e2e8f0; background: #f1f5f9; color: #64748b; cursor: not-allowed; opacity: 1; }
        .unit { position: absolute; top: 50%; right: 14px; color: #91a2ba; font-size: .86rem; font-weight: 600; transform: translateY(-50%); pointer-events: none; }
        .hint { margin: 5px 0 0; color: #71859a; font-size: .72rem; line-height: 1.4; }
        .error { margin: 6px 0 0; color: #b42318; font-size: .78rem; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
        .submit, .reset { display: inline-flex; align-items: center; justify-content: center; min-height: 42px; padding: 0 17px; border-radius: 8px; font: inherit; font-size: .9rem; font-weight: 700; text-decoration: none; cursor: pointer; }
        .submit { border: 1px solid #0b6484; background: #0b6484; color: #fff; }
        .submit:hover { background: #095773; }
        .reset { border: 1px solid #d7e1ed; background: #fff; color: #34445b; }
        .reset:hover { background: #f7f9fc; }
        .result-card { min-width: 0; height: 100%; padding: 30px 32px; color: #24364a; font-family: Poppins, "Segoe UI", sans-serif; scroll-margin-top: 24px; overflow-wrap: anywhere; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
        .result-head { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr); gap: 24px; align-items: center; padding-bottom: 22px; border-bottom: 1px solid #e5edf3; }
        .result-score { min-width: 0; padding-right: 20px; border-right: 1px solid #e5edf3; }
        .score-caption { margin: 0 0 7px; color: #43566b; font-size: .95rem; font-weight: 600; }
        .score-value { margin: 0; color: #172c41; font-size: clamp(2rem, 3.4vw, 2.8rem); font-weight: 800; letter-spacing: -.06em; line-height: 1.15; white-space: nowrap; font-variant-numeric: tabular-nums; }
        .score-value small { color: #637b91; font-size: .9rem; font-weight: 500; letter-spacing: 0; white-space: nowrap; }
        .result-status { min-width: 0; }
        .summary-label { margin: 0 0 8px; color: #344b61; font-size: .95rem; font-weight: 600; }
        .status-badge { display: inline-flex; align-items: center; gap: 9px; min-height: 42px; padding: 7px 16px; border: 1px solid transparent; border-radius: 999px; font-size: 1rem; font-weight: 700; }
        .status-icon { font-size: 1.15rem; line-height: 1; }
        .status-aman { border-color: #b8dfc7; background: #e9f6ed; color: #21663b; }
        .status-waspada { border-color: #f0d39a; background: #fff5df; color: #815000; }
        .status-kritis { border-color: #f2bfc1; background: #fff0ef; color: #9c292d; }
        .result-explanation { margin: 20px 0 16px; color: #344b61; font-size: 1rem; line-height: 1.6; }
        .recommendation { margin: 0; color: #344b61; font-size: 1rem; line-height: 1.6; }
        .recommendation strong { display: block; margin-bottom: 4px; color: #263a50; }
        .application-action { display: inline-flex; align-items: center; justify-content: center; min-height: 42px; margin-top: 18px; padding: 0 17px; border: 1px solid #9aafbf; border-radius: 8px; background: #e8eff3; color: #526a7c; font: inherit; font-size: .9rem; font-weight: 700; cursor: not-allowed; }
        .application-note { margin: 8px 0 0; color: #71859a; font-size: .8rem; line-height: 1.45; }
        @media (max-width: 1100px) {
            .has-result .fields { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 760px) {
            .topbar-inner { height: 68px; padding: 0 16px; }
            .brand-wrap { gap: 0; }
            .topbar-label { display: none; }
            .account-copy { max-width: 110px; font-size: .74rem; }
            .account-copy small { font-size: .66rem; white-space: nowrap; }
            .account { gap: 7px; margin-left: 8px; }
            .brand { font-size: 1.08rem; }
            .brand-mark { flex-basis: 34px; width: 34px; height: 34px; }
            .app-frame { grid-template-columns: 1fr; }
            .sidebar { padding: 14px 14px 12px; border-right: 0; border-bottom: 1px solid #e0e8ef; }
            .sidebar-label { margin-bottom: 8px; }
            .side-nav { display: flex; overflow-x: auto; gap: 6px; padding-bottom: 2px; }
            .side-link { flex: 0 0 auto; min-height: 42px; padding: 0 12px; }
            .side-link.disabled { display: none; }
            .sidebar-help, .sidebar-footer { display: none; }
            .shell { padding: 30px 18px 44px; }
            .form-card { padding: 22px; }
            .has-result .form-card { padding: 22px; }
            .result-card { padding: 22px; }
            .result-head { grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr); gap: 16px; }
            .result-score { padding-right: 14px; }
            .score-value { font-size: clamp(1.8rem, 7vw, 2.5rem); }
            .result-footer { justify-content: flex-start; }
        }
        @media (max-width: 560px) {
            :root { font-size: 14px; }
            .shell { padding: 24px 14px 36px; }
            .form-card, .has-result .form-card, .result-card { padding: 18px 16px; }
            .layout { gap: 14px; margin-top: 18px; }
            .section-title { margin-top: 18px; padding-top: 14px; }
            .fields, .has-result .fields { grid-template-columns: minmax(0, 1fr); gap: 14px; }
            .has-result .field-wide { grid-column: auto; }
            .has-result .field-wide label { white-space: normal; }
            .result-head { grid-template-columns: 1fr; gap: 16px; }
            .result-score { padding: 0 0 14px; border-right: 0; border-bottom: 1px solid #e5edf3; }
            .result-status { padding-top: 0; }
            .actions { display: grid; grid-template-columns: minmax(0, 1fr); }
            .submit, .reset { width: 100%; }
            select, input { font-size: 16px; }
        }
        @media (max-width: 360px) {
            .topbar-inner { padding: 0 12px; }
            .shell { padding-right: 10px; padding-left: 10px; }
            .form-card, .has-result .form-card, .result-card { padding: 16px 12px; }
            .score-value { font-size: 1.9rem; }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <div class="brand-wrap">
            <div class="brand"><span class="brand-mark" aria-hidden="true">💧</span><span>Lingga Air<small>LAYANAN BANTUAN AIR BERSIH</small></span></div>
            <span class="topbar-label">Portal pelayanan desa</span>
        </div>
        <div class="account">
            <span class="account-copy">
                {{ auth()->user()->username }}
                <small>Desa {{ $desa->nama_desa }}</small>
            </span>
            <span class="account-avatar" aria-hidden="true">{{ Illuminate\Support\Str::upper(Illuminate\Support\Str::substr(auth()->user()->username, 0, 1)) }}</span>
        </div>
    </div>
</header>
<div class="app-frame">
    <aside class="sidebar">
        <nav aria-label="Navigasi desa">
            <p class="sidebar-label">Layanan desa</p>
            <div class="side-nav">
                <span class="side-link disabled" aria-disabled="true"><span class="side-icon" aria-hidden="true">▦</span>Beranda</span>
                <a class="side-link active" href="{{ route('cek-kelayakan.index') }}" aria-current="page"><span class="side-icon" aria-hidden="true">✓</span>Cek Kelayakan</a>
                <span class="side-link disabled" aria-disabled="true"><span class="side-icon" aria-hidden="true">＋</span>Pengajuan Bantuan</span>
                <span class="side-link disabled" aria-disabled="true"><span class="side-icon" aria-hidden="true">▤</span>Status Pengajuan</span>
                <span class="side-link disabled" aria-disabled="true"><span class="side-icon" aria-hidden="true">?</span>Bantuan &amp; Panduan</span>
            </div>
        </nav>
        <section class="sidebar-help" aria-label="Bantuan teknis">
            <div class="sidebar-help-icon" aria-hidden="true">☎</div>
            <strong>Butuh bantuan?</strong>
            <p>Hubungi tim teknis untuk bantuan penggunaan layanan desa.</p>
        </section>
        <p class="sidebar-footer">© 2026 Lingga Air<br>Pemerintah Kabupaten Purbalingga</p>
    </aside>
    <div class="main-content">
<main class="shell">
    <header>
        <div class="eyebrow">Layanan desa</div>
        <h1>Cek Kelayakan Bantuan Air Bersih</h1>
        <p class="intro">Periksa kondisi kekeringan desa dan pahami tingkat prioritas penanganannya berdasarkan data yang dimasukkan.</p>
    </header>

    <div class="layout {{ isset($result) ? 'has-result' : 'no-result' }}">
        <section class="card form-card" aria-labelledby="form-title">
            <h2 id="form-title">Data simulasi</h2>
            <p class="section-copy">Data desa, kecamatan, dan kependudukan diambil dari profil akun Anda. Isi kondisi lapangan terbaru untuk menjalankan simulasi.</p>

            <form method="POST" action="{{ route('cek-kelayakan.calculate') }}">
                @csrf
                <div class="section-title">Profil desa</div>
                @php
                @endphp
                <p class="section-copy"><strong>{{ $desa->nama_desa }}</strong> · Kecamatan {{ $desa->kecamatan }}</p>
                <div class="fields">
                    @foreach ([
                        'jumlah_kk_total'    => ['Jumlah KK total', 'Jumlah seluruh kepala keluarga di desa.', 'KK'],
                        'jumlah_jiwa_total'  => ['Jumlah jiwa total', 'Jumlah seluruh penduduk di desa.', 'jiwa'],
                        'jumlah_balita'      => ['Jumlah balita', 'Jumlah anak berusia di bawah lima tahun di desa.', 'jiwa'],
                        'jumlah_lansia'      => ['Jumlah lansia', 'Jumlah penduduk lanjut usia di desa.', 'jiwa'],
                        'jumlah_disabilitas' => ['Jumlah penyandang disabilitas', 'Jumlah penduduk penyandang disabilitas di desa.', 'jiwa'],
                    ] as $name => [$label, $hint, $unit])
                        <div class="{{ $name === 'jumlah_disabilitas' ? 'field-wide' : '' }}">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <div class="input-wrap">
                                <input id="{{ $name }}" type="number" min="{{ in_array($name, ['jumlah_kk_total', 'jumlah_jiwa_total'], true) ? 1 : 0 }}" step="1" value="{{ $desa->{$name} ?? '' }}" disabled aria-disabled="true">
                                <span class="unit">{{ $unit }}</span>
                            </div>
                            <p class="hint">{{ $hint }}</p>
                        </div>
                    @endforeach
                </div>
                <p class="hint">Data ini diambil dari profil desa dan tidak dapat diubah pada halaman cek kelayakan.</p>
                @error('kk_terdampak')<p class="error">{{ $message }}</p>@enderror

                <div class="section-title">Kondisi lapangan</div>
                <div class="fields">
                    @foreach ([
                        'kk_terdampak'       => ['Jumlah KK terdampak', 'Tidak boleh melebihi jumlah KK total.', 'KK'],
                        'hari_tanpa_air'      => ['Hari tanpa air', 'Skor normalisasi mencapai maksimum pada 7 hari.', 'hari'],
                        'jarak_sumber_meter'  => ['Jarak ke sumber air', 'Skor normalisasi mencapai maksimum pada 2.000 meter.', 'meter'],
                    ] as $name => [$label, $hint, $unit])
                        <div>
                            <label for="{{ $name }}">{{ $label }}</label>
                            <div class="input-wrap">
                                <input id="{{ $name }}" name="{{ $name }}" type="number" min="0" step="1" value="{{ old($name, $input[$name] ?? '') }}" required>
                                <span class="unit">{{ $unit }}</span>
                            </div>
                            <p class="hint">{{ $hint }}</p>
                            @error($name)<p class="error">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>
                <div class="actions">
                    <button class="submit" type="submit">Periksa Kondisi Desa</button>
                    <a class="reset" href="{{ route('cek-kelayakan.index') }}">Reset</a>
                </div>
            </form>
        </section>

        @if (isset($result))
            @php
                $statusInfo = match ($result['status']) {
                    'kritis' => [
                        'label' => 'Kritis',
                        'icon' => '!',
                        'meaning' => 'Desa membutuhkan bantuan segera.',
                        'action' => 'Segera ajukan bantuan air bersih dan siapkan surat permohonan Kepala Desa berstempel basah serta foto kondisi lapangan.',
                    ],
                    'waspada' => [
                        'label' => 'Waspada',
                        'icon' => '⚠',
                        'meaning' => 'Desa mulai kesulitan air dan perlu dipantau.',
                        'action' => 'Siapkan surat permohonan Kepala Desa berstempel basah dan foto kondisi lapangan. Anda dapat mengajukan sekarang atau memantau dulu.',
                    ],
                    default => [
                        'label' => 'Aman',
                        'icon' => '✓',
                        'meaning' => 'Kondisi desa relatif terkendali.',
                        'action' => 'Belum perlu mengajukan bantuan; pantau ketersediaan air dan cek ulang bila kesulitan air bertambah.',
                    ],
                };
                $reasonText = 'Sumber air terdekat berjarak '
                    . number_format((int) $input['jarak_sumber_meter'], 0, ',', '.') . ' meter, desa sudah '
                    . number_format((int) $input['hari_tanpa_air'], 0, ',', '.') . ' hari kesulitan air, dan '
                    . number_format((int) $input['kk_terdampak'], 0, ',', '.') . ' dari '
                    . number_format((int) $input['jumlah_kk_total'], 0, ',', '.') . ' KK ('
                    . number_format(((int) $input['kk_terdampak'] / (int) $input['jumlah_kk_total']) * 100, 0, ',', '.') . '%) terdampak.';
            @endphp

            <aside id="hasil-cek" class="card result-card" aria-labelledby="hasil-cek-title" aria-live="polite" tabindex="-1">
                <h2 id="hasil-cek-title" class="sr-only">Hasil Cek Kondisi</h2>
                <div class="result-head">
                    <div class="result-score">
                        <p class="score-caption">Tingkat kedaruratan</p>
                        <p class="score-value">{{ number_format($result['score'], 2, ',', '.') }} <small>/ 100</small></p>
                    </div>
                    <div class="result-status">
                        <p class="summary-label">Kategori kondisi</p>
                        <span class="status-badge status-{{ $result['status'] }}">
                            <span class="status-icon" aria-hidden="true">{{ $statusInfo['icon'] }}</span>
                            {{ $statusInfo['label'] }}
                        </span>
                    </div>
                </div>
                <p class="result-explanation">{{ $statusInfo['meaning'] }} {{ $reasonText }}</p>
                <p class="recommendation"><strong>Rekomendasi tindak lanjut</strong>{{ $statusInfo['action'] }}</p>
                @if (in_array($result['status'], ['waspada', 'kritis'], true))
                    <button class="application-action" type="button" disabled aria-describedby="application-note">Ajukan Pengajuan</button>
                    <p class="application-note" id="application-note">Halaman pengajuan belum tersedia.</p>
                @endif

            </aside>
        @endif
    </div>
</main>
    </div>
</div>
@if (isset($result))
    <script>
        document.getElementById('hasil-cek')?.scrollIntoView();
    </script>
@endif
</body>
</html>
