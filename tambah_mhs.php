<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nim = $_POST['nim'];
    $namamhs = $_POST['namamhs'];

    // Menggunakan tabel tbl_mhs dan kolom database: nim dan namamhs
    $query = "INSERT INTO tbl_mhs (nim, namamhs) VALUES ('$nim', '$namamhs')";
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Data mahasiswa berhasil ditambah!'); window.location='querymhs.php';</script>";
    } else {
        echo "<div class='alert error'>❌ Gagal: " . mysqli_error($link) . "</div>";
    }
}
?>

<div class="form-card">
    <div class="form-card-title">✨ Tambah Data Mahasiswa Baru</div>
    <div class="form-card-subtitle">Isi semua field di bawah untuk menambahkan mahasiswa baru</div>

    <form action="" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>NIM (Nomor Induk Mahasiswa)</label>
                <input type="text" name="nim" required placeholder="Masukkan NIM mahasiswa">
            </div>
            <div class="form-group">
                <label>Nama Lengkap Mahasiswa</label>
                <input type="text" name="namamhs" required placeholder="Masukkan nama lengkap mahasiswa">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="simpan" class="btn btn-success">💾 Simpan</button>
            <a href="querymhs.php" class="btn btn-secondary">✕ Kembali</a>
        </div>
    </form>
</div>