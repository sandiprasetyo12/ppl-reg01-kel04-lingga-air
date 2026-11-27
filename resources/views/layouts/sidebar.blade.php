@php
    $sidebarUser = auth()->user();

    // Deteksi role aktif: bisa dipaksa lewat parameter ['role' => 'admin' | 'desa' | 'penyalur' | 'guest']
    // atau otomatis dideteksi dari akun yang sedang login
    $resolvedRole = $role ?? (
        $sidebarUser?->isBpbd() ? 'admin' : (
            $sidebarUser?->isDesa() ? 'desa' : (
                $sidebarUser?->isPenyalur() ? 'penyalur' : 'guest'
            )
        )
    );

    // Normalisasi role string
    if (in_array($resolvedRole, ['bpbd', 'admin'], true)) {
        $resolvedRole = 'admin';
    } elseif (in_array($resolvedRole, ['desa', 'pemohon'], true)) {
        $resolvedRole = 'desa';
    }

    // Perwakilan Desa tidak menggunakan sidebar (menggunakan topbar navigasi)
    if ($resolvedRole === 'desa') {
        return;
    }

    // Konfigurasi per role: brand route, label, dan daftar menu
    $sidebarConfig = match($resolvedRole) {
        'admin' => [
            'aria' => 'Sidebar Admin BPBD',
            'brandRoute' => Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard'),
            'section' => 'BPBD',
            'menus' => [
                ['Dashboard', 'fa-table-cells-large', 'admin.dashboard', request()->routeIs('admin.dashboard')],
                ['Verifikasi Pengajuan', 'fa-circle-check', 'verifikasi.index', request()->routeIs('verifikasi.*')],
                ['Pengumuman', 'fa-bullhorn', 'admin.pengumuman.index', request()->routeIs('admin.pengumuman.*')],
                ['Penugasan Penyalur', 'fa-user-group', null, false],
                ['Peta Distribusi', 'fa-map', 'admin.pemetaan.index', request()->routeIs('admin.pemetaan.*')],
                ['Laporan Penyaluran', 'fa-chart-column', 'laporan.index', request()->routeIs('laporan.*')],
                ['Master Data Desa', 'fa-database', 'admin.desa.index', request()->routeIs('admin.desa.*')],
                ['Feedback', 'fa-comment-dots', null, false],
            ],
        ],
        'desa' => [
            'aria' => 'Sidebar Perwakilan Desa',
            'brandRoute' => Route::has('desa.dashboard') ? route('desa.dashboard') : url('/desa/dashboard'),
            'section' => 'Layanan Desa',
            'menus' => [
                ['Beranda', 'fa-house', 'desa.dashboard', request()->routeIs('desa.dashboard')],
                ['Cek Kelayakan', 'fa-circle-check', 'cek-kelayakan.index', request()->routeIs('cek-kelayakan.*')],
                ['Pengajuan Bantuan', 'fa-file-circle-plus', 'pengajuan.create', request()->routeIs('pengajuan.create')],
                ['Riwayat Pengajuan', 'fa-file-lines', 'pengajuan.index', request()->routeIs('pengajuan.index') || request()->routeIs('pengajuan.show')],
                ['Lacak Pengajuan', 'fa-location-dot', null, false],
                ['Feedback', 'fa-comment-dots', null, false],
            ],
        ],
        'penyalur' => [
            'aria' => 'Sidebar Mitra Penyalur',
            'brandRoute' => Route::has('penyalur.dashboard') ? route('penyalur.dashboard') : url('/penyalur/dashboard'),
            'section' => 'Mitra Penyalur',
            'menus' => [
                ['Dashboard Penyalur', 'fa-table-cells-large', 'penyalur.dashboard', request()->routeIs('penyalur.dashboard')],
                ['Laporan Penyaluran', 'fa-chart-column', 'penyalur.laporan.index', request()->routeIs('penyalur.laporan.*')],
            ],
        ],
        default => [
            'aria' => 'Menu Lingga Air',
            'brandRoute' => url('/'),
            'section' => 'Menu',
            'menus' => [
                ['Beranda', 'fa-house', 'home', request()->routeIs('home')],
                ['Masuk', 'fa-arrow-right-to-bracket', 'login', request()->routeIs('login')],
            ],
        ],
    };

    // Parameter untuk menampilkan / menyembunyikan header (default: tampil)
    $renderHeader = $showHeader ?? true;

    // Data user untuk topbar
    $headerRole = $sidebarUser?->isBpbd()
        ? 'Admin BPBD'
        : ($sidebarUser?->isDesa() ? 'Perwakilan Desa' : ($sidebarUser?->isPenyalur() ? 'Mitra Penyalur' : 'Pengguna'));
    $headerName = $sidebarUser?->username ?? 'Pengguna';
    $headerInitial = Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($headerName, 0, 2));
@endphp

{{-- ==================== TOPBAR HEADER ==================== --}}
@if ($renderHeader)
    <header class="role-header">
        <div class="role-header-actions">
            <button type="button" class="role-header-notifications" aria-label="Notifikasi">
                <i class="fa-regular fa-bell" aria-hidden="true"></i>
                <span class="role-header-notification-dot" aria-hidden="true"></span>
            </button>

            <div class="role-header-account">
                <button type="button" class="role-header-account-toggle" data-role-header-toggle aria-expanded="false" aria-haspopup="true">
                    <span class="role-header-avatar" aria-hidden="true">{{ $headerInitial }}</span>
                    <span class="role-header-account-copy">
                        <strong>{{ $headerName }}</strong>
                        <small>{{ $headerRole }}</small>
                    </span>
                    <i class="fa-solid fa-chevron-down role-header-chevron" aria-hidden="true"></i>
                </button>
                <div class="role-header-dropdown" data-role-header-menu hidden>
                    <span class="role-header-dropdown-item" aria-disabled="true">
                        <i class="fa-regular fa-user" aria-hidden="true"></i>Profil saya
                    </span>
                    <span class="role-header-dropdown-item" aria-disabled="true">
                        <i class="fa-solid fa-gear" aria-hidden="true"></i>Pengaturan akun
                    </span>
                    <hr class="role-header-dropdown-divider">
                    @if ($sidebarUser)
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="role-header-dropdown-item role-header-logout">
                                <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>Keluar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </header>
@endif

{{-- ==================== SIDEBAR ==================== --}}
<aside class="role-sidebar" aria-label="{{ $sidebarConfig['aria'] }}">
    {{-- Brand Logo & Tombol Ciutkan --}}
    <div class="role-sidebar-brand-row">
        <a href="{{ $sidebarConfig['brandRoute'] }}" class="role-sidebar-brand" aria-label="Lingga Air">
            <img src="{{ asset('images/logo-lingga-oneline.png') }}" alt="Lingga Air" class="role-sidebar-brand-full">
            <img src="{{ asset('images/logo-lingga-icon.png') }}" alt="" aria-hidden="true" class="role-sidebar-brand-icon">
        </a>
        <button type="button" class="role-sidebar-toggle" data-role-sidebar-toggle aria-label="Ciutkan sidebar" aria-expanded="true">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>
    </div>

    {{-- Menu Navigasi Utama --}}
    <nav aria-label="{{ $sidebarConfig['aria'] }}">
        <p class="role-sidebar-section">{{ $sidebarConfig['section'] }}</p>
        <div class="role-sidebar-nav">
            @foreach ($sidebarConfig['menus'] as [$label, $icon, $routeName, $active])
                @if ($routeName && Route::has($routeName))
                    <a href="{{ route($routeName) }}" @if ($active) aria-current="page" @endif class="role-sidebar-link {{ $active ? 'active' : '' }}">
                        <i class="fa-solid {{ $icon }}" aria-hidden="true"></i>
                        <span class="role-sidebar-link-label">{{ $label }}</span>
                    </a>
                @else
                    <span class="role-sidebar-link disabled" aria-disabled="true" title="Menu belum tersedia">
                        <i class="fa-solid {{ $icon }}" aria-hidden="true"></i>
                        <span class="role-sidebar-link-label">{{ $label }}</span>
                    </span>
                @endif
            @endforeach
        </div>
    </nav>

    {{-- Footer Sidebar --}}
    <p class="role-sidebar-footer">&copy; {{ date('Y') }} Lingga Air<br>Pemerintah Kabupaten Purbalingga</p>
</aside>

{{-- ==================== SCRIPTS ==================== --}}
<script>
    // Toggle Collapse Sidebar
    if (!window.__sidebarToggleRegistered) {
        window.__sidebarToggleRegistered = true;
        document.addEventListener('click', (event) => {
            const toggle = event.target.closest('[data-role-sidebar-toggle]');
            if (!toggle) return;

            const shell = toggle.closest('.role-sidebar-shell');
            if (!shell) return;

            const collapsed = shell.classList.toggle('is-sidebar-collapsed');
            toggle.setAttribute('aria-expanded', String(!collapsed));
            toggle.setAttribute('aria-label', collapsed ? 'Lebarkan sidebar' : 'Ciutkan sidebar');
            toggle.innerHTML = `<i class="fa-solid ${collapsed ? 'fa-chevron-right' : 'fa-chevron-left'}" aria-hidden="true"></i>`;
        });
    }

    // Toggle Dropdown Profile Header
    if (!window.__headerToggleRegistered) {
        window.__headerToggleRegistered = true;
        document.addEventListener('click', (event) => {
            const toggle = event.target.closest('[data-role-header-toggle]');
            const openMenu = document.querySelector('[data-role-header-menu]:not([hidden])');

            if (toggle) {
                const menu = toggle.parentElement.querySelector('[data-role-header-menu]');
                const shouldOpen = menu.hidden;
                if (openMenu && openMenu !== menu) {
                    openMenu.hidden = true;
                    openMenu.parentElement.querySelector('[data-role-header-toggle]')?.setAttribute('aria-expanded', 'false');
                }
                menu.hidden = !shouldOpen;
                toggle.setAttribute('aria-expanded', String(shouldOpen));
                return;
            }

            if (openMenu && !event.target.closest('[data-role-header-menu]')) {
                openMenu.hidden = true;
                openMenu.parentElement.querySelector('[data-role-header-toggle]')?.setAttribute('aria-expanded', 'false');
            }
        });
    }
</script>
