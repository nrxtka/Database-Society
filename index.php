<?php include "atas.php"; ?>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <h1>🏠 Beranda</h1>
        <p>Selamat datang di Sistem Informasi Akademik Universitas Djuanda</p>
    </div>
    <div class="topbar-right">
        <span class="badge-count">2026</span>
    </div>
</div>

<?php
include "koneksi.php";
$total_mhs = mysqli_query($link, "SELECT COUNT(*) as c FROM tbl_mhs");
$total_mhs = $total_mhs ? mysqli_fetch_assoc($total_mhs)['c'] : 0;

$total_dos = mysqli_query($link, "SELECT COUNT(*) as c FROM tbl_dosen");
$total_dos = $total_dos ? mysqli_fetch_assoc($total_dos)['c'] : 0;

$total_mk  = mysqli_query($link, "SELECT COUNT(*) as c FROM tbl_matakuliah");
$total_mk  = $total_mk  ? mysqli_fetch_assoc($total_mk)['c']  : 0;

$total_nil = mysqli_query($link, "SELECT COUNT(*) as c FROM tbl_nilai");
$total_nil = $total_nil ? mysqli_fetch_assoc($total_nil)['c'] : 0;

$total_dopem = mysqli_query($link, "SELECT COUNT(*) as c FROM tbl_dopem");
$total_dopem = $total_dopem ? mysqli_fetch_assoc($total_dopem)['c'] : 0;
?>

<!-- STATS -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon cyan">👥</div>
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
    <div class="stat-card">
        <div class="stat-icon amber">📚</div>
        <div class="stat-body">
            <h3><?= $total_mk ?></h3>
            <p>Mata Kuliah</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose">💯</div>
        <div class="stat-body">
            <h3><?= $total_nil ?></h3>
            <p>Data Nilai</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">🔗</div>
        <div class="stat-body">
            <h3><?= $total_dopem ?></h3>
            <p>Data DOPEM</p>
        </div>
    </div>
</div>

<!-- WELCOME CARD -->
<div class="content-card" style="text-align:center; padding: 48px 32px;">
    <img src="unidaclear.png" width="160" style="margin-bottom:28px; filter:drop-shadow(0 6px 16px rgba(37,99,235,.25));">
    <h2 style="font-size:22px; font-weight:800; color:var(--text-1); margin-bottom:10px;">Selamat Datang!</h2>
    <p style="font-size:14px; color:var(--text-2); line-height:1.7; max-width:480px; margin:0 auto 28px;">
        Sistem Informasi Akademik membantu pengelolaan data mahasiswa, dosen, mata kuliah, nilai, dan dosen pembimbing secara terintegrasi.
    </p>
    <div style="display:flex; flex-wrap:wrap; gap:12px; justify-content:center;">
        <a href="querymhs.php" class="btn btn-primary">👥 Data Mahasiswa</a>
        <a href="dosen.php"    class="btn btn-secondary">👨‍🏫 Data Dosen</a>
        <a href="mata_kuliah.php" class="btn btn-secondary">📚 Mata Kuliah</a>
        <a href="nilai.php"    class="btn btn-secondary">💯 Nilai</a>
        <a href="dopem.php"    class="btn btn-secondary">🔗 DOPEM</a>
    </div>
</div>

<?php include "bawah.php"; ?>
