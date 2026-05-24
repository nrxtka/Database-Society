<?php
include "koneksi.php";

$nid_Target = $_GET['nid'];
$query = "DELETE FROM tbl_dosen WHERE nid = '$nid_Target'";

if (mysqli_query($link, $query)) {
    echo "<script>alert('Data berhasil dihapus!'); window.location='index.php?page=dosen';</script>";
} else {
    echo "<script>alert('Gagal menghapus data!'); window.location='index.php?page=dosen';</script>";
}
?>