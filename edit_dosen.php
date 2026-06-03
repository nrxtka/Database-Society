<?php
include "koneksi.php";

// Pastikan parameter NID ada di URL
if (isset($_GET['nid'])) {
    $nid_Target = $_GET['nid'];
    
    // Mengambil data dosen berdasarkan NID target
    $query = "SELECT * FROM tbl_dosen WHERE nid = '$nid_Target'";
    $result = mysqli_query($link, $query);
    $data = mysqli_fetch_assoc($result);

    // Jika data tidak ditemukan di database
    if (!$data) {
        echo "<script>alert('Data dosen tidak ditemukan!'); window.location='dosen.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('NID tidak ditentukan!'); window.location='dosen.php';</script>";
    exit;
}

if (isset($_POST['ubah'])) {
    $namadosen = $_POST['namadosen'];

    // Update data ke kolom namados sesuai database kelompok
    $update = "UPDATE tbl_dosen SET namados = '$namadosen' WHERE nid = '$nid_Target'";
    if (mysqli_query($link, $update)) {
        // PERBAIKAN: Mengarahkan kembali ke dosen.php (bukan index.php)
        echo "<script>alert('Data berhasil diubah!'); window.location='dosen.php';</script>";
    } else {
        echo "<div class='alert error'>❌ Gagal mengupdate: " . mysqli_error($link) . "</div>";
    }
}
?>

<div class="form-card">
    <div class="form-card-title">✏️ Edit Data Dosen Pembimbing</div>
    <div class="form-card-subtitle">Ubah data yang ingin diperbaiki, lalu klik Update</div>

    <form action="" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>NID (Nomor Induk Dosen)</label>
                <input type="text" value="<?= htmlspecialchars($data['nid']) ?>" disabled>
                <span class="form-hint">* NID tidak dapat diubah karena merupakan Primary Key.</span>
            </div>
            <div class="form-group">
                <label>Nama Lengkap Dosen</label>
                <input type="text" name="namadosen" value="<?= htmlspecialchars($data['namados']) ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="ubah" class="btn btn-warning">🔄 Update Data</button>
            <a href="dosen.php" class="btn btn-secondary">✕ Batal</a>
        </div>
    </form>
</div>
