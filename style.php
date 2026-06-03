<?php
/**
 * style.php — Design System Terpusat
 * Semua halaman include file ini untuk tampilan yang konsisten.
 * Berdasarkan desain dari dopem.php.
 */
?>
<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --bg: #f0f4ff;
        --surface: #fff;
        --primary: #2563eb;
        --primary-d: #1d4ed8;
        --primary-l: #eff6ff;
        --accent: #06b6d4;
        --accent-l: #ecfeff;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --text-1: #0f172a;
        --text-2: #475569;
        --text-3: #94a3b8;
        --border: #e2e8f0;
        --shadow-sm: 0 1px 3px rgba(37,99,235,.08), 0 1px 2px rgba(0,0,0,.06);
        --shadow-md: 0 4px 24px rgba(37,99,235,.12), 0 2px 8px rgba(0,0,0,.06);
        --shadow-lg: 0 12px 40px rgba(37,99,235,.18), 0 4px 16px rgba(0,0,0,.08);
        --radius: 14px;
        --radius-sm: 8px;
        --radius-lg: 20px;
        --font: 'Plus Jakarta Sans', sans-serif;
        --mono: 'Space Mono', monospace;
    }

    html { scroll-behavior: smooth }

    body {
        font-family: var(--font);
        background: var(--bg);
        color: var(--text-1);
        min-height: 100vh;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 0;
        background:
            radial-gradient(ellipse 80% 50% at 10% 10%, rgba(37,99,235,.10) 0%, transparent 60%),
            radial-gradient(ellipse 60% 40% at 90% 80%, rgba(6,182,212,.08) 0%, transparent 60%);
    }

    /* ── SIDEBAR ── */
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        width: 210px;
        height: 100vh;
        background: var(--surface);
        border-right: 1px solid var(--border);
        box-shadow: var(--shadow-md);
        display: flex;
        flex-direction: column;
        z-index: 100;
    }

    .sidebar-brand {
        padding: 24px 20px 18px;
        border-bottom: 1px solid var(--border);
    }

    .sidebar-brand .logo {
        width: 38px; height: 38px;
        background: var(--primary);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(37,99,235,.35);
    }

    .sidebar-brand h2 { font-size: 14px; font-weight: 800; color: var(--text-1) }
    .sidebar-brand p  { font-size: 11px; color: var(--text-3); margin-top: 2px }

    .sidebar-nav { flex: 1; padding: 16px 10px; overflow-y: auto }

    .nav-label {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-3);
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 12px 12px 6px;
    }

    .nav-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px;
        border-radius: var(--radius-sm);
        color: var(--text-2);
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all .18s;
        margin-bottom: 2px;
    }

    .nav-item:hover { background: var(--primary-l); color: var(--primary) }

    .nav-item.active {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 4px 12px rgba(37,99,235,.30);
    }

    .nav-item .icon {
        width: 28px; height: 28px;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        background: var(--bg);
    }

    .nav-item.active .icon { background: rgba(255,255,255,.20) }

    .sidebar-footer {
        padding: 12px 10px;
        border-top: 1px solid var(--border);
    }

    .sidebar-footer a {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px;
        border-radius: var(--radius-sm);
        color: var(--text-2);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: background .18s;
    }

    .sidebar-footer a:hover { background: #fee2e2; color: var(--danger) }

    /* ── MAIN CONTENT ── */
    .main {
        margin-left: 210px;
        min-height: 100vh;
        padding: 28px 32px;
        position: relative;
        z-index: 1;
    }

    /* ── TOPBAR ── */
    .topbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 28px;
    }

    .topbar-left h1 {
        font-size: 22px; font-weight: 800; color: var(--text-1); letter-spacing: -.5px;
    }

    .topbar-left p { font-size: 13px; color: var(--text-3); margin-top: 3px }

    .topbar-right { display: flex; align-items: center; gap: 12px }

    .badge-count {
        background: var(--primary-l); color: var(--primary);
        border-radius: 20px; padding: 5px 14px;
        font-size: 12px; font-weight: 700; font-family: var(--mono);
    }

    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--primary); color: #fff;
        border: none; border-radius: var(--radius-sm);
        padding: 10px 20px; font-size: 13px; font-weight: 700;
        font-family: var(--font); cursor: pointer;
        box-shadow: 0 4px 12px rgba(37,99,235,.30);
        transition: all .2s; text-decoration: none;
    }

    .btn-add:hover {
        background: var(--primary-d);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37,99,235,.40);
    }

    .btn-add.active { background: #475569 }

    /* ── STATS ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        display: flex; align-items: center; gap: 14px;
        transition: transform .2s, box-shadow .2s;
    }

    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md) }

    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }

    .stat-icon.blue  { background: var(--primary-l) }
    .stat-icon.cyan  { background: var(--accent-l) }
    .stat-icon.green { background: #ecfdf5 }
    .stat-icon.amber { background: #fffbeb }
    .stat-icon.rose  { background: #fff1f2 }

    .stat-body h3 { font-size: 22px; font-weight: 800; font-family: var(--mono); color: var(--text-1) }
    .stat-body p  { font-size: 12px; color: var(--text-3); font-weight: 500 }

    /* ── ALERTS ── */
    .alert {
        border-radius: var(--radius-sm);
        padding: 13px 16px;
        margin-bottom: 20px;
        font-size: 13.5px; font-weight: 600;
        display: flex; align-items: center; gap: 10px;
        animation: slideDown .3s ease;
    }

    .alert.success { background:#ecfdf5; color:var(--success); border:1px solid #a7f3d0 }
    .alert.error   { background:#fef2f2; color:var(--danger);  border:1px solid #fecaca }
    .alert.warning { background:#fffbeb; color:var(--warning); border:1px solid #fde68a }
    .alert.info    { background:var(--primary-l); color:var(--primary); border:1px solid #bfdbfe }

    /* ── FORM CARD ── */
    .form-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        animation: slideDown .3s ease;
    }

    .form-card-title {
        font-size: 15px; font-weight: 800; margin-bottom: 4px;
        color: var(--text-1); display: flex; align-items: center; gap: 8px;
    }

    .form-card-subtitle { font-size: 12.5px; color: var(--text-3); margin-bottom: 20px }

    .form-section-label {
        font-size: 11px; font-weight: 700; color: var(--text-3);
        letter-spacing: .08em; text-transform: uppercase;
        margin-bottom: 12px; padding-bottom: 8px;
        border-bottom: 1px dashed var(--border);
        display: flex; align-items: center; gap: 8px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px; margin-bottom: 14px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 14px; margin-bottom: 14px;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px }

    .form-group label {
        font-size: 11px; font-weight: 700; color: var(--text-2);
        letter-spacing: .04em; text-transform: uppercase;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 11px 14px;
        font-size: 14px; font-family: var(--font);
        color: var(--text-1); background: #fafbff;
        transition: border-color .18s, box-shadow .18s, background .18s;
        outline: none;
    }

    .form-group select { cursor: pointer }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        background: #fff;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder { color: var(--text-3) }

    .form-group input:disabled,
    .form-group input[readonly] {
        background: #f1f5f9; color: var(--text-3); cursor: not-allowed;
    }

    .form-hint { font-size: 11px; color: var(--text-3); margin-top: 3px }

    .form-actions {
        display: flex; gap: 10px; margin-top: 18px;
        padding-top: 16px; border-top: 1px solid var(--border);
    }

    /* ── BUTTONS ── */
    .btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 13.5px; font-weight: 700; font-family: var(--font);
        cursor: pointer; transition: all .18s; text-decoration: none;
        white-space: nowrap;
    }

    .btn-primary   { background:var(--primary);  color:#fff; box-shadow:0 4px 12px rgba(37,99,235,.25) }
    .btn-primary:hover { background:var(--primary-d); transform:translateY(-1px) }

    .btn-success   { background:var(--success);  color:#fff; box-shadow:0 4px 12px rgba(16,185,129,.25) }
    .btn-success:hover { background:#059669; transform:translateY(-1px) }

    .btn-warning   { background:var(--warning);  color:#fff; box-shadow:0 4px 12px rgba(245,158,11,.25) }
    .btn-warning:hover { background:#d97706; transform:translateY(-1px) }

    .btn-danger    { background:var(--danger);   color:#fff; box-shadow:0 4px 12px rgba(239,68,68,.25) }
    .btn-danger:hover  { background:#dc2626 }

    .btn-secondary { background:var(--bg); color:var(--text-2); border:1.5px solid var(--border) }
    .btn-secondary:hover { background:var(--border); color:var(--text-1) }

    .btn-sm { padding: 7px 14px; font-size: 12px }

    /* ── TABLE CARD ── */
    .table-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .table-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        background: linear-gradient(135deg, #f8faff 0%, #fff 100%);
        flex-wrap: wrap; gap: 12px;
    }

    .table-header h2 {
        font-size: 14px; font-weight: 800; color: var(--text-1);
        display: flex; align-items: center; gap: 8px;
    }

    .table-header .sub { font-size: 12px; color: var(--text-3); font-weight: 500; margin-top: 2px }

    .search-wrap { position: relative }

    .search-wrap input {
        border: 1.5px solid var(--border);
        border-radius: 30px;
        padding: 8px 16px 8px 36px;
        font-size: 13px; font-family: var(--font);
        outline: none; background: var(--bg);
        transition: all .18s; width: 210px;
    }

    .search-wrap input:focus { border-color: var(--primary); background: #fff }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-3); font-size: 14px }

    .table-wrap { overflow-x: auto }

    table { width: 100%; border-collapse: collapse }

    thead { background: #f8faff }

    thead th {
        padding: 12px 18px; text-align: left;
        font-size: 11px; font-weight: 700; color: var(--text-3);
        letter-spacing: .06em; text-transform: uppercase;
        border-bottom: 1px solid var(--border); white-space: nowrap;
    }

    thead th.center { text-align: center }

    tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .12s;
    }

    tbody tr:last-child { border-bottom: none }
    tbody tr:hover { background: var(--primary-l) }

    tbody td { padding: 13px 18px; font-size: 13.5px; vertical-align: middle }
    tbody td.center { text-align: center }

    .td-no { font-family: var(--mono); font-size: 12px; color: var(--text-3); font-weight: 700 }

    /* ── BADGES ── */
    .badge {
        font-family: var(--mono); font-size: 12px; font-weight: 700;
        padding: 3px 10px; border-radius: 20px; display: inline-block;
    }

    .badge-blue  { background: var(--primary-l); color: var(--primary) }
    .badge-cyan  { background: var(--accent-l);  color: var(--accent) }
    .badge-green { background: #ecfdf5; color: var(--success) }
    .badge-amber { background: #fffbeb; color: var(--warning) }
    .badge-red   { background: #fef2f2; color: var(--danger) }
    .badge-gray  { background: #f1f5f9; color: var(--text-2) }

    .name-cell    { font-weight: 600; color: var(--text-1) }
    .name-sub     { font-weight: 500; color: var(--text-2) }

    /* ── ACTION BUTTONS ── */
    .actions { display: flex; gap: 6px; justify-content: center }

    .btn-action {
        width: 32px; height: 32px;
        border-radius: 8px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px; transition: all .18s; text-decoration: none;
    }

    .btn-edit { background: #fff7ed; color: var(--warning) }
    .btn-edit:hover { background: var(--warning); color: #fff; transform: scale(1.1) }

    .btn-del  { background: #fef2f2; color: var(--danger) }
    .btn-del:hover  { background: var(--danger);  color: #fff; transform: scale(1.1) }

    .btn-view { background: var(--primary-l); color: var(--primary) }
    .btn-view:hover { background: var(--primary); color: #fff; transform: scale(1.1) }

    /* ── EMPTY STATE ── */
    .empty { padding: 50px; text-align: center }
    .empty .icon { font-size: 44px; margin-bottom: 12px }
    .empty h3 { font-size: 15px; font-weight: 700; color: var(--text-2) }
    .empty p  { font-size: 13px; color: var(--text-3); margin-top: 6px }

    /* ── TABLE FOOTER ── */
    .table-footer {
        padding: 14px 22px;
        border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        background: #f8faff; flex-wrap: wrap; gap: 8px;
    }

    .table-footer span { font-size: 12px; color: var(--text-3); font-weight: 500 }

    /* ── MODAL ── */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(15,23,42,.5); backdrop-filter: blur(4px);
        z-index: 999; align-items: center; justify-content: center;
    }

    .modal-overlay.show { display: flex; animation: fadeIn .2s ease }

    .modal-box {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 36px; max-width: 380px; width: 90%;
        text-align: center; box-shadow: var(--shadow-lg);
    }

    .modal-icon {
        width: 60px; height: 60px; background: #fef2f2; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; margin: 0 auto 14px;
    }

    .modal-box h3  { font-size: 17px; font-weight: 800; margin-bottom: 8px }
    .modal-box p   { font-size: 13px; color: var(--text-2); margin-bottom: 22px; line-height: 1.5 }
    .modal-actions { display: flex; gap: 10px; justify-content: center }

    /* ── CONTENT CARD ── */
    .content-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 28px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        margin-bottom: 24px;
    }

    .content-card h3 {
        font-size: 15px; font-weight: 800; color: var(--text-1);
        margin-bottom: 4px; display: flex; align-items: center; gap: 8px;
    }

    .content-card .sub { font-size: 12px; color: var(--text-3); margin-bottom: 18px }

    /* ── DIVIDER ── */
    .divider {
        border: none; border-top: 1px solid var(--border);
        margin: 20px 0;
    }

    /* ── ANIMATIONS ── */
    @keyframes slideDown {
        from { opacity:0; transform:translateY(-12px) }
        to   { opacity:1; transform:translateY(0) }
    }

    @keyframes fadeIn {
        from { opacity:0 }
        to   { opacity:1 }
    }

    /* ── SCROLLBAR ── */
    ::-webkit-scrollbar { width: 5px; height: 5px }
    ::-webkit-scrollbar-track { background: transparent }
    ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px }
    ::-webkit-scrollbar-thumb:hover { background: var(--primary) }
</style>
