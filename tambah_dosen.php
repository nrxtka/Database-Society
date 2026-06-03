<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nid = $_POST['nid'];
    $namadosen = $_POST['namadosen'];

    // Menyesuaikan kolom database: nid dan namados
    $query = "INSERT INTO tbl_dosen (nid, namados) VALUES ('$nid', '$namadosen')";
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Data berhasil ditambah!'); window.location='dosen.php';</script>";
    } else {
        echo "<div class='alert error'>❌ Gagal: " . mysqli_error($link) . "</div>";
    }
}
?>

<div class="form-card">
    <div class="form-card-title">✨ Tambah Data Dosen Baru</div>
    <div class="form-card-subtitle">Isi semua field di bawah untuk menambahkan dosen baru</div>

    <form action="" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>NID (Nomor Induk Dosen)</label>
                <input type="text" name="nid" required placeholder="Masukkan NID dosen">
            </div>
            <div class="form-group">
                <label>Nama Lengkap Dosen</label>
                <input type="text" name="namadosen" required placeholder="Nama lengkap beserta gelar">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="simpan" class="btn btn-success">💾 Simpan</button>
            <a href="dosen.php" class="btn btn-secondary">✕ Kembali</a>
        </div>
    </form>
</div>
