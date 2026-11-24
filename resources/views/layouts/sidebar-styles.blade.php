<style>
    /* =========================================================================
       SIDEBAR & SHELL LAYOUT STYLES
       ========================================================================= */
    .role-sidebar-shell {
        display: grid;
        grid-template-columns: 220px minmax(0, 1fr) !important;
        grid-template-rows: 58px minmax(0, 1fr);
        min-width: 0;
        min-height: 100vh;
    }
    .role-sidebar-shell.is-sidebar-collapsed {
        grid-template-columns: 76px minmax(0, 1fr) !important;
    }
    .role-sidebar-shell > .role-header { grid-column: 2; grid-row: 1; }
    .role-sidebar-shell > .role-sidebar { grid-column: 1; grid-row: 1 / span 2; }
    .role-sidebar-shell > .dashboard-main,
    .role-sidebar-shell > .main-content,
    .role-sidebar-shell > .role-admin-main { grid-column: 2; grid-row: 2; min-width: 0; }
    .role-sidebar {
        position: relative;
        z-index: 60;
        display: flex;
        min-width: 0;
        flex-direction: column;
        padding: 0 14px 16px;
        border-right: 1px solid #EAECF0;
        background: #fff;
    }
    .role-sidebar-brand-row {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: 58px;
        padding: 0 4px;
        border-bottom: 1px solid #EAECF0;
    }
    .role-sidebar-brand {
        display: inline-flex;
        min-width: 0;
        align-items: center;
        text-decoration: none;
    }
    .role-sidebar-brand img {
        display: block;
        width: 150px;
        max-width: 100%;
        max-height: 38px;
        object-fit: contain;
        object-position: left center;
    }
    .role-sidebar-brand-icon { display: none !important; }
    .role-sidebar-toggle {
        position: absolute;
        right: -28px;
        bottom: -14px;
        z-index: 70;
        display: grid;
        width: 28px;
        height: 28px;
        place-items: center;
        border: 1px solid #EAECF0;
        border-radius: 50%;
        background: #fff;
        color: #667085;
        font-size: .75rem;
        box-shadow: 0 1px 4px rgb(16 24 40 / 10%);
        cursor: pointer;
    }
    .role-sidebar-toggle:hover { color: #00629B; }
    .role-sidebar-section {
        margin: 10px 0 7px 10px;
        color: #667085;
        font-size: .62rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .role-sidebar-nav { display: grid; gap: 4px; }
    .role-sidebar-link {
        display: flex;
        min-width: 0;
        min-height: 36px;
        align-items: center;
        gap: 10px;
        padding: 0 10px;
        border-radius: 9px;
        color: #344054;
        font-size: .69rem;
        font-weight: 500;
        text-decoration: none;
        transition: background .14s ease, color .14s ease;
    }
    .role-sidebar-link > i {
        display: grid;
        flex: 0 0 16px;
        place-items: center;
        color: #667085;
        font-size: .78rem;
    }
    .role-sidebar-link:hover:not(.active):not(.disabled) { background: #F2F4F7; color: #00629B; }
    .role-sidebar-link.active { background: #00629B; color: #fff; font-weight: 700; }
    .role-sidebar-link.active > i { color: #fff; }
    .role-sidebar-link.disabled { color: #475467; }
    .role-sidebar-link.disabled > i { color: #667085; }
    .role-sidebar-link-label { min-width: 0; }
    .role-sidebar-divider { height: 1px; margin: 13px 4px; border: 0; background: #EAECF0; }
    .role-sidebar-utility { display: grid; gap: 4px; }
    .role-sidebar-footer {
        margin: auto 4px 0;
        padding-top: 18px;
        color: #98A2B3;
        font-size: .62rem;
        line-height: 1.5;
    }

    .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-section,
    .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-link-label,
    .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-footer { display: none; }
    .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-brand-full { display: none; }
    .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-brand-icon {
        display: block !important;
        width: 28px;
        height: 28px;
    }
    .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-brand-row,
    .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-link { justify-content: center; }
    .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-link { padding: 0; }

    /* =========================================================================
       HEADER STYLES
       ========================================================================= */
    .role-header {
        position: relative;
        z-index: 50;
        display: flex;
        min-width: 0;
        min-height: 58px;
        align-items: center;
        gap: 22px;
        padding: 7px 24px;
        border-bottom: 1px solid #EAECF0;
        background: #fff;
    }
    .role-header-search { display: flex; min-width: 80px; max-width: 520px; height: 36px; flex: 1 1 280px; align-items: center; gap: 9px; padding: 0 11px; border: 1px solid #EAECF0; border-radius: 8px; background: #F8FAFC; color: #98A2B3; }
    .role-header-search i { font-size: .75rem; }
    .role-header-search input { width: 100%; min-width: 0; border: 0; outline: 0; background: transparent; color: #344054; font: inherit; font-size: .68rem; }
    .role-header-search input::placeholder { color: #98A2B3; }
    .role-header-actions { display: flex; align-items: center; gap: 14px; margin-left: auto; }
    .role-header-notifications { position: relative; display: grid; width: 30px; height: 34px; place-items: center; border: 0; background: transparent; color: #475467; font-size: .95rem; }
    .role-header-notification-dot { position: absolute; top: 4px; right: 2px; width: 7px; height: 7px; border: 1px solid #fff; border-radius: 50%; background: #F04438; }
    .role-header-account { position: relative; }
    .role-header-account-toggle { display: flex; align-items: center; gap: 9px; padding: 2px 0; border: 0; background: transparent; color: #101828; text-align: left; }
    .role-header-avatar { display: grid; width: 30px; height: 30px; flex: 0 0 30px; place-items: center; border-radius: 50%; background: #EAF4FA; color: #00629B; font-size: .66rem; font-weight: 700; }
    .role-header-account-copy { display: grid; gap: 1px; }
    .role-header-account-copy strong { max-width: 135px; overflow: hidden; font-size: .68rem; font-weight: 650; text-overflow: ellipsis; white-space: nowrap; }
    .role-header-account-copy small { color: #667085; font-size: .59rem; }
    .role-header-chevron { margin-left: 3px; color: #667085; font-size: .62rem; }
    .role-header-dropdown { position: absolute; top: calc(100% + 12px); right: 0; z-index: 100; width: 204px; padding: 7px; border: 1px solid #EAECF0; border-radius: 12px; background: #fff; box-shadow: 0 12px 28px rgb(16 24 40 / 15%); }
    .role-header-dropdown[hidden] { display: none; }
    .role-header-dropdown-item { display: flex; width: 100%; min-height: 36px; align-items: center; gap: 10px; padding: 0 9px; border: 0; border-radius: 7px; background: transparent; color: #344054; font: inherit; font-size: .72rem; text-align: left; text-decoration: none; }
    .role-header-dropdown-item i { width: 14px; color: #667085; text-align: center; }
    .role-header-dropdown-item[aria-disabled="true"] { color: #98A2B3; cursor: default; }
    .role-header-dropdown-divider { height: 1px; margin: 5px 2px; border: 0; background: #EAECF0; }
    .role-header-dropdown form { margin: 0; }
    .role-header-logout { color: #344054; cursor: pointer; }
    .role-header-logout:hover, .role-header-account-toggle:hover { color: #00629B; }

    /* =========================================================================
       RESPONSIVE (MOBILE) STYLES
       ========================================================================= */
    @media (max-width: 760px) {
        .role-sidebar-shell,
        .role-sidebar-shell.is-sidebar-collapsed {
            grid-template-columns: minmax(0, 1fr) !important;
            grid-template-rows: auto;
        }
        .role-sidebar-shell > .role-header,
        .role-sidebar-shell > .role-sidebar,
        .role-sidebar-shell > .dashboard-main,
        .role-sidebar-shell > .main-content,
        .role-sidebar-shell > .role-admin-main { grid-column: 1; grid-row: auto; }
        .role-sidebar { padding: 8px 12px 12px; border-right: 0; border-bottom: 1px solid #EAECF0; }
        .role-sidebar-brand-row { min-height: 46px; }
        .role-sidebar-brand img { width: 122px; max-height: 32px; }
        .role-sidebar-toggle { position: static; margin-left: auto; }
        .role-sidebar-nav,
        .role-sidebar-utility { display: flex; gap: 5px; overflow-x: auto; padding-bottom: 3px; }
        .role-sidebar-link { flex: 0 0 auto; min-height: 36px; white-space: nowrap; }
        .role-sidebar-divider,
        .role-sidebar-section,
        .role-sidebar-footer { display: none; }
        .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-link-label { display: initial; }
        .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-brand-full { display: block; }
        .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-brand-icon { display: none !important; }
        .role-sidebar-shell.is-sidebar-collapsed .role-sidebar-link { justify-content: flex-start; }

        .role-header { min-height: 54px; gap: 12px; padding: 7px 14px; }
        .role-header-search { height: 33px; }
        .role-header-search input { font-size: .64rem; }
        .role-header-actions { gap: 8px; }
        .role-header-account-copy { display: none; }
    }
    @media (max-width: 430px) {
        .role-header { gap: 8px; padding-right: 10px; padding-left: 10px; }
        .role-header-search { flex-basis: 90px; padding: 0 8px; }
    }
</style>
