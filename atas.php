<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "proteksi.php";
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Akademik — Universitas Djuanda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <?php include "style.php"; ?>
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
        <div class="nav-label">Menu Utama</div>

        <a href="index.php" class="nav-item <?= $current_page === 'index.php' ? 'active' : '' ?>">
            <span class="icon">🏠</span> Beranda
        </a>

        <div class="nav-label">Master Data</div>

        <a href="querymhs.php" class="nav-item <?= $current_page === 'querymhs.php' ? 'active' : '' ?>">
            <span class="icon">👥</span> Mahasiswa
        </a>

        <a href="dosen.php" class="nav-item <?= $current_page === 'dosen.php' ? 'active' : '' ?>">
            <span class="icon">👨‍🏫</span> Dosen
        </a>

        <a href="mata_kuliah.php" class="nav-item <?= $current_page === 'mata_kuliah.php' ? 'active' : '' ?>">
            <span class="icon">📚</span> Mata Kuliah
        </a>

        <a href="nilai.php" class="nav-item <?= $current_page === 'nilai.php' ? 'active' : '' ?>">
            <span class="icon">💯</span> Nilai Akademik
        </a>

        <a href="dopem.php" class="nav-item <?= $current_page === 'dopem.php' ? 'active' : '' ?>">
            <span class="icon">🔗</span> DOPEM
        </a>

        <a href="anggota.php" class="nav-item <?= $current_page === 'anggota.php' ? 'active' : '' ?>">
            <span class="icon">🪪</span> Anggota
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php">
            <span style="font-size:15px;">🚪</span> Keluar
        </a>
    </div>
</aside>

<!-- MAIN -->
<main class="main">
