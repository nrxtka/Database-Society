<?php
include "koneksi.php";

// Pastikan parameter NID ada di URL sebelum melakukan eksekusi
if (isset($_GET['nid'])) {
    $nid_Target = $_GET['nid'];
    
    // Query untuk menghapus data berdasarkan NID target
    $query = "DELETE FROM tbl_dosen WHERE nid = '$nid_Target'";

    if (mysqli_query($link, $query)) {
        // PERBAIKAN: Mengarahkan kembali ke dosen.php (bukan index.php)
        echo "<script>alert('Data berhasil dihapus!'); window.location='dosen.php';</script>";
    } else {
        // PERBAIKAN: Mengarahkan kembali ke dosen.php (bukan index.php)
        echo "<script>alert('Gagal menghapus data!'); window.location='dosen.php';</script>";
    }
} else {
    // Jika diakses langsung tanpa membawa NID di URL
    echo "<script>alert('NID tidak ditemukan!'); window.location='dosen.php';</script>";
}
?>