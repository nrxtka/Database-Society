<?php
// =============================================
// KONFIGURASI DATABASE
// =============================================
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "basisdata2026";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("<div style='padding:20px;color:red;font-family:sans-serif;'>❌ Koneksi gagal: " . $conn->connect_error . "</div>");
}

$message      = "";
$message_type = "";
$show_form    = false;

// =============================================
// DELETE
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['nim'])) {
    $nim = $conn->real_escape_string($_GET['nim']);
    $del = $conn->query("DELETE FROM tbl_dopem WHERE nim = '$nim'");
    $message      = $del ? "✅ Data berhasil dihapus." : "❌ Gagal menghapus: " . $conn->error;
    $message_type = $del ? "success" : "error";
}

// =============================================
// INSERT — input manual nim, nama_mhs, nid, nama_dosen
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $nim      = $conn->real_escape_string(trim($_POST['nim']));
    $namamhs  = $conn->real_escape_string(trim($_POST['namamhs']));
    $nid      = $conn->real_escape_string(trim($_POST['nid']));
    $namados  = $conn->real_escape_string(trim($_POST['namados']));

    if ($nim && $namamhs && $nid && $namados) {
        // 1. Insert ke tbl_mhs kalau nim belum ada
        $cek_mhs = $conn->query("SELECT nim FROM tbl_mhs WHERE nim='$nim'");
        if ($cek_mhs->num_rows === 0) {
            $conn->query("INSERT INTO tbl_mhs (nim, namamhs) VALUES ('$nim','$namamhs')");
        }

        // 2. Insert ke tbl_dosen kalau nid belum ada
        $cek_dos = $conn->query("SELECT nid FROM tbl_dosen WHERE nid='$nid'");
        if ($cek_dos->num_rows === 0) {
            $conn->query("INSERT INTO tbl_dosen (nid, namados) VALUES ('$nid','$namados')");
        }

        // 3. Insert ke tbl_dopem
        $cek_dop = $conn->query("SELECT nim FROM tbl_dopem WHERE nim='$nim'");
        if ($cek_dop->num_rows > 0) {
            $message      = "⚠️ NIM $nim sudah terdaftar di DOPEM.";
            $message_type = "warning";
            $show_form    = true;
        } else {
            $ins          = $conn->query("INSERT INTO tbl_dopem (nim, nid) VALUES ('$nim','$nid')");
            $message      = $ins ? "✅ Data berhasil ditambahkan." : "❌ Gagal insert DOPEM: " . $conn->error;
            $message_type = $ins ? "success" : "error";
            $show_form    = !$ins;
        }
    } else {
        $message      = "⚠️ Semua field wajib diisi.";
        $message_type = "warning";
        $show_form    = true;
    }
}

// =============================================
// UPDATE — edit manual
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $old_nim = $conn->real_escape_string(trim($_POST['old_nim']));
    $nim     = $conn->real_escape_string(trim($_POST['nim']));
    $namamhs = $conn->real_escape_string(trim($_POST['namamhs']));
    $nid     = $conn->real_escape_string(trim($_POST['nid']));
    $namados = $conn->real_escape_string(trim($_POST['namados']));

    if ($nim && $namamhs && $nid && $namados) {
        // Update tbl_mhs
        $conn->query("UPDATE tbl_mhs SET nim='$nim', namamhs='$namamhs' WHERE nim='$old_nim'");
        // Update tbl_dosen (namados berdasarkan nid lama)
        $old_nid_res = $conn->query("SELECT nid FROM tbl_dopem WHERE nim='$old_nim'");
        $old_nid_row = $old_nid_res ? $old_nid_res->fetch_assoc() : null;
        $old_nid     = $old_nid_row ? $conn->real_escape_string($old_nid_row['nid']) : $nid;
        $conn->query("UPDATE tbl_dosen SET nid='$nid', namados='$namados' WHERE nid='$old_nid'");
        // Update tbl_dopem
        $upd          = $conn->query("UPDATE tbl_dopem SET nim='$nim', nid='$nid' WHERE nim='$old_nim'");
        $message      = $upd ? "✅ Data berhasil diperbarui." : "❌ Gagal update: " . $conn->error;
        $message_type = $upd ? "success" : "error";
    } else {
        $message      = "⚠️ Semua field wajib diisi.";
        $message_type = "warning";
    }
}

// =============================================
// DATA UNTUK EDIT
// =============================================
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nim'])) {
    $edit_nim = $conn->real_escape_string($_GET['nim']);
    $edit_res = $conn->query("
        SELECT d.nim, m.namamhs, d.nid, ds.namados
        FROM tbl_dopem d
        LEFT JOIN tbl_mhs   m  ON d.nim = m.nim
        LEFT JOIN tbl_dosen ds ON d.nid = ds.nid
        WHERE d.nim = '$edit_nim'
    ");
    if ($edit_res && $edit_res->num_rows > 0) $edit_data = $edit_res->fetch_assoc();
}

// =============================================
// DATA DOPEM (JOIN)
// =============================================
$res_dopem  = $conn->query("
    SELECT d.nim, m.namamhs, d.nid, ds.namados
    FROM   tbl_dopem d
    LEFT JOIN tbl_mhs    m  ON d.nim = m.nim
    LEFT JOIN tbl_dosen  ds ON d.nid = ds.nid
    ORDER BY d.nim ASC
");
$dopem_data = [];
if ($res_dopem) while ($r = $res_dopem->fetch_assoc()) $dopem_data[] = $r;
$total = count($dopem_data);

// Count mhs & dosen
$total_mhs = $conn->query("SELECT COUNT(*) as c FROM tbl_mhs")->fetch_assoc()['c'];
$total_dos = $conn->query("SELECT COUNT(*) as c FROM tbl_dosen")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOPEM — Basis Data 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
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
            --shadow-sm: 0 1px 3px rgba(37, 99, 235, .08), 0 1px 2px rgba(0, 0, 0, .06);
            --shadow-md: 0 4px 24px rgba(37, 99, 235, .12), 0 2px 8px rgba(0, 0, 0, .06);
            --shadow-lg: 0 12px 40px rgba(37, 99, 235, .18), 0 4px 16px rgba(0, 0, 0, .08);
            --radius: 14px;
            --radius-sm: 8px;
            --radius-lg: 20px;
            --font: 'Plus Jakarta Sans', sans-serif;
            --mono: 'Space Mono', monospace;
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text-1);
            min-height: 100vh;
            overflow-x: hidden
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 10% 10%, rgba(37, 99, 235, .10) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 90% 80%, rgba(6, 182, 212, .08) 0%, transparent 60%);
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
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
            border-bottom: 1px solid var(--border)
        }

        .sidebar-brand .logo {
            width: 38px;
            height: 38px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .35);
        }

        .sidebar-brand h2 {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-1)
        }

        .sidebar-brand p {
            font-size: 11px;
            color: var(--text-3);
            margin-top: 2px
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 10px
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-2);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all .18s;
            margin-bottom: 3px;
        }

        .nav-item:hover {
            background: var(--primary-l);
            color: var(--primary)
        }

        .nav-item.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .30)
        }

        .nav-item .icon {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            background: var(--bg);
        }

        .nav-item.active .icon {
            background: rgba(255, 255, 255, .20)
        }

        .sidebar-footer {
            padding: 14px 10px;
            border-top: 1px solid var(--border)
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-2);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: background .18s;
        }

        .sidebar-footer a:hover {
            background: var(--primary-l);
            color: var(--primary)
        }

        /* MAIN */
        .main {
            margin-left: 200px;
            min-height: 100vh;
            padding: 28px 32px;
            position: relative;
            z-index: 1
        }

        /* TOPBAR */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px
        }

        .topbar-left h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-1);
            letter-spacing: -.5px
        }

        .topbar-left p {
            font-size: 13px;
            color: var(--text-3);
            margin-top: 3px
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .badge-count {
            background: var(--primary-l);
            color: var(--primary);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 700;
            font-family: var(--mono);
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 10px 20px;
            font-size: 13.5px;
            font-weight: 700;
            font-family: var(--font);
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .30);
            transition: all .2s;
        }

        .btn-add:hover {
            background: var(--primary-d);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, .40)
        }

        .btn-add.active {
            background: #475569
        }

        /* STATS */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md)
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0
        }

        .stat-icon.blue {
            background: var(--primary-l)
        }

        .stat-icon.cyan {
            background: var(--accent-l)
        }

        .stat-icon.green {
            background: #ecfdf5
        }

        .stat-body h3 {
            font-size: 22px;
            font-weight: 800;
            font-family: var(--mono);
            color: var(--text-1)
        }

        .stat-body p {
            font-size: 12px;
            color: var(--text-3);
            font-weight: 500
        }

        /* ALERT */
        .alert {
            border-radius: var(--radius-sm);
            padding: 13px 16px;
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown .3s ease;
        }

        .alert.success {
            background: #ecfdf5;
            color: var(--success);
            border: 1px solid #a7f3d0
        }

        .alert.error {
            background: #fef2f2;
            color: var(--danger);
            border: 1px solid #fecaca
        }

        .alert.warning {
            background: #fffbeb;
            color: var(--warning);
            border: 1px solid #fde68a
        }

        /* FORM CARD */
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
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 6px;
            color: var(--text-1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-card-subtitle {
            font-size: 12.5px;
            color: var(--text-3);
            margin-bottom: 20px
        }

        /* Divider antar section */
        .form-section-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-3);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px dashed var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 14px
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .form-group label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-2);
            letter-spacing: .04em;
            text-transform: uppercase
        }

        .form-group input {
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 11px 14px;
            font-size: 14px;
            font-family: var(--font);
            color: var(--text-1);
            background: #fafbff;
            transition: border-color .18s, box-shadow .18s, background .18s;
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
            background: #fff;
        }

        .form-group input::placeholder {
            color: var(--text-3)
        }

        .form-group input:hover:not(:focus) {
            border-color: #94a3b8
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid var(--border)
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            border: none;
            font-size: 13.5px;
            font-weight: 700;
            font-family: var(--font);
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .25)
        }

        .btn-primary:hover {
            background: var(--primary-d);
            transform: translateY(-1px)
        }

        .btn-secondary {
            background: var(--bg);
            color: var(--text-2);
            border: 1.5px solid var(--border)
        }

        .btn-secondary:hover {
            background: var(--border);
            color: var(--text-1)
        }

        /* TABLE */
        .table-card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            overflow: hidden
        }

        .table-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #f8faff 0%, #fff 100%);
        }

        .table-header h2 {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-1);
            display: flex;
            align-items: center;
            gap: 8px
        }

        .table-header .sub {
            font-size: 12px;
            color: var(--text-3);
            font-weight: 500;
            margin-top: 2px
        }

        .search-wrap {
            position: relative
        }

        .search-wrap input {
            border: 1.5px solid var(--border);
            border-radius: 30px;
            padding: 8px 16px 8px 36px;
            font-size: 13px;
            font-family: var(--font);
            outline: none;
            background: var(--bg);
            transition: all .18s;
            width: 210px;
        }

        .search-wrap input:focus {
            border-color: var(--primary);
            background: #fff
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-3);
            font-size: 14px
        }

        .table-wrap {
            overflow-x: auto
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        thead {
            background: #f8faff
        }

        thead th {
            padding: 12px 18px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-3);
            letter-spacing: .06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        thead th.center {
            text-align: center
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .12s
        }

        tbody tr:last-child {
            border-bottom: none
        }

        tbody tr:hover {
            background: var(--primary-l)
        }

        tbody td {
            padding: 13px 18px;
            font-size: 13.5px;
            vertical-align: middle
        }

        tbody td.center {
            text-align: center
        }

        .td-no {
            font-family: var(--mono);
            font-size: 12px;
            color: var(--text-3);
            font-weight: 700
        }

        .nim-badge {
            font-family: var(--mono);
            font-size: 12px;
            font-weight: 700;
            background: var(--primary-l);
            color: var(--primary);
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block
        }

        .nid-badge {
            font-family: var(--mono);
            font-size: 12px;
            font-weight: 700;
            background: var(--accent-l);
            color: var(--accent);
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block
        }

        .name-cell {
            font-weight: 600;
            color: var(--text-1)
        }

        .name-dosen {
            font-weight: 500;
            color: var(--text-2)
        }

        .actions {
            display: flex;
            gap: 6px;
            justify-content: center
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all .18s;
            text-decoration: none;
        }

        .btn-edit {
            background: #fff7ed;
            color: var(--warning)
        }

        .btn-edit:hover {
            background: var(--warning);
            color: #fff;
            transform: scale(1.1)
        }

        .btn-del {
            background: #fef2f2;
            color: var(--danger)
        }

        .btn-del:hover {
            background: var(--danger);
            color: #fff;
            transform: scale(1.1)
        }

        .empty {
            padding: 50px;
            text-align: center
        }

        .empty .icon {
            font-size: 44px;
            margin-bottom: 12px
        }

        .empty h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-2)
        }

        .empty p {
            font-size: 13px;
            color: var(--text-3);
            margin-top: 6px
        }

        .table-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8faff;
        }

        .table-footer span {
            font-size: 12px;
            color: var(--text-3);
            font-weight: 500
        }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .5);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex;
            animation: fadeIn .2s ease
        }

        .modal-box {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 36px;
            max-width: 380px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }

        .modal-icon {
            width: 60px;
            height: 60px;
            background: #fef2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 14px
        }

        .modal-box h3 {
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 8px
        }

        .modal-box p {
            font-size: 13px;
            color: var(--text-2);
            margin-bottom: 22px;
            line-height: 1.5
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: center
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, .25)
        }

        .btn-danger:hover {
            background: #dc2626
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-12px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px
        }

        ::-webkit-scrollbar-track {
            background: transparent
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 10px
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary)
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo">🎓</div>
            <h2>Basis Data 2026</h2>
            <p>Universitas Djuanda</p>
        </div>
        <nav class="sidebar-nav">
            <a href="dopem.php" class="nav-item active">
                <span class="icon">🔗</span>
                DOPEM
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="index.php">
                <span class="icon">🏠</span>
                Kembali ke Home
            </a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <h1>🔗 Data DOPEM</h1>
                <p>Manajemen data Dosen Pembimbing Mahasiswa</p>
            </div>
            <div class="topbar-right">
                <span class="badge-count"><?= $total ?> Records</span>
                <button class="btn-add" id="btnTambah" onclick="toggleForm()">＋ Tambah Data</button>
            </div>
        </div>

        <!-- STATS -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue">🔗</div>
                <div class="stat-body">
                    <h3><?= $total ?></h3>
                    <p>Total Data DOPEM</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon cyan">👨‍🎓</div>
                <div class="stat-body">
                    <h3><?= $total_mhs ?></h3>
                    <p>Total Mahasiswa</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">👨‍🏫</div>
                <div class="stat-body">
                    <h3><?= $total_dos ?></h3>
                    <p>Total Dosen</p>
                </div>
            </div>
        </div>

        <!-- ALERT -->
        <?php if ($message): ?>
            <div class="alert <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- FORM TAMBAH -->
        <div id="form-tambah" class="form-card" style="display:<?= $show_form ? 'block' : 'none' ?>;">
            <div class="form-card-title">✨ Tambah Data DOPEM</div>
            <div class="form-card-subtitle">Isi semua field di bawah untuk menambahkan data baru</div>

            <form method="POST" action="dopem.php">
                <input type="hidden" name="action" value="insert">

                <div class="form-section-label">👨‍🎓 Data Mahasiswa</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" name="nim" placeholder="Contoh: 1016" required
                            value="<?= isset($_POST['nim']) && $show_form ? htmlspecialchars($_POST['nim']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Nama Mahasiswa</label>
                        <input type="text" name="namamhs" placeholder="Contoh: Tony Stark" required
                            value="<?= isset($_POST['namamhs']) && $show_form ? htmlspecialchars($_POST['namamhs']) : '' ?>">
                    </div>
                </div>

                <div class="form-section-label">👨‍🏫 Data Dosen</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>NID</label>
                        <input type="text" name="nid" placeholder="Contoh: 888" required
                            value="<?= isset($_POST['nid']) && $show_form ? htmlspecialchars($_POST['nid']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Nama Dosen</label>
                        <input type="text" name="namados" placeholder="Contoh: Dr. Ahmad" required
                            value="<?= isset($_POST['namados']) && $show_form ? htmlspecialchars($_POST['namados']) : '' ?>">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
                    <button type="button" class="btn btn-secondary" onclick="toggleForm()">✕ Tutup</button>
                </div>
            </form>
        </div>

        <!-- FORM EDIT -->
        <?php if ($edit_data): ?>
            <div class="form-card">
                <div class="form-card-title">✏️ Edit Data DOPEM</div>
                <div class="form-card-subtitle">Ubah data yang ingin diperbaiki, lalu klik Update</div>

                <form method="POST" action="dopem.php">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="old_nim" value="<?= htmlspecialchars($edit_data['nim']) ?>">

                    <div class="form-section-label">👨‍🎓 Data Mahasiswa</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>NIM</label>
                            <input type="text" name="nim" required value="<?= htmlspecialchars($edit_data['nim']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Nama Mahasiswa</label>
                            <input type="text" name="namamhs" required value="<?= htmlspecialchars($edit_data['namamhs'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-section-label">👨‍🏫 Data Dosen</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>NID</label>
                            <input type="text" name="nid" required value="<?= htmlspecialchars($edit_data['nid']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Nama Dosen</label>
                            <input type="text" name="namados" required value="<?= htmlspecialchars($edit_data['namados'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">🔄 Update Data</button>
                        <a href="dopem.php" class="btn btn-secondary">✕ Batal</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- TABEL -->
        <div class="table-card">
            <div class="table-header">
                <div>
                    <h2>📋 Tabel DOPEM</h2>
                    <div class="sub">Data gabungan Mahasiswa dan Dosen Pembimbing</div>
                </div>
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" placeholder="Cari data..." oninput="filterTable()">
                </div>
            </div>

            <div class="table-wrap">
                <table id="dopemTable">
                    <thead>
                        <tr>
                            <th class="center">NO</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>NID</th>
                            <th>Nama Dosen</th>
                            <th class="center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dopem_data)): ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty">
                                        <div class="icon">📭</div>
                                        <h3>Belum Ada Data</h3>
                                        <p>Klik tombol "Tambah Data" untuk menambahkan data DOPEM.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dopem_data as $i => $row): ?>
                                <tr>
                                    <td class="td-no center"><?= $i + 1 ?></td>
                                    <td><span class="nim-badge"><?= htmlspecialchars($row['nim']) ?></span></td>
                                    <td><span class="name-cell"><?= htmlspecialchars($row['namamhs'] ?? '—') ?></span></td>
                                    <td><span class="nid-badge"><?= htmlspecialchars($row['nid']) ?></span></td>
                                    <td><span class="name-dosen"><?= htmlspecialchars($row['namados'] ?? '—') ?></span></td>
                                    <td class="center">
                                        <div class="actions">
                                            <a href="dopem.php?action=edit&nim=<?= urlencode($row['nim']) ?>"
                                                class="btn-action btn-edit" title="Edit">✏️</a>
                                            <button class="btn-action btn-del" title="Hapus"
                                                onclick="confirmDelete('<?= htmlspecialchars($row['nim']) ?>','<?= htmlspecialchars(addslashes($row['namamhs'] ?? '')) ?>')">🗑️</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span>Menampilkan <strong><?= $total ?></strong> data DOPEM</span>
                <span>Basis Data 2026 — Universitas Djuanda</span>
            </div>
        </div>

    </main>

    <!-- MODAL HAPUS -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">🗑️</div>
            <h3>Hapus Data?</h3>
            <p id="deleteMsg">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <a id="deleteBtn" href="#" class="btn btn-danger">Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script>
        function toggleForm() {
            const el = document.getElementById('form-tambah');
            const btn = document.getElementById('btnTambah');
            const open = el.style.display === 'none' || el.style.display === '';
            el.style.display = open ? 'block' : 'none';
            btn.textContent = open ? '✕ Tutup Form' : '＋ Tambah Data';
            btn.classList.toggle('active', open);
            if (open) el.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function confirmDelete(nim, nama) {
            document.getElementById('deleteMsg').textContent =
                'Hapus data DOPEM untuk mahasiswa "' + nama + '" (NIM: ' + nim + ')?';
            document.getElementById('deleteBtn').href = 'dopem.php?action=delete&nim=' + encodeURIComponent(nim);
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        const alertEl = document.querySelector('.alert');
        if (alertEl) {
            setTimeout(() => {
                alertEl.style.transition = 'opacity .5s';
                alertEl.style.opacity = '0';
                setTimeout(() => alertEl.remove(), 500);
            }, 4000);
        }

        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('#dopemTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        }

        <?php if ($edit_data): ?>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelector('.form-card')?.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        <?php endif; ?>
    </script>
</body>

</html>
<?php $conn->close(); ?>