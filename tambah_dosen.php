<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nid = $_POST['nid'];
    $namadosen = $_POST['namadosen'];

    // Menyesuaikan kolom database kelompok: namados
    $query = "INSERT INTO tbl_dosen (nid, namados) VALUES ('$nid', '$namadosen')";
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Data berhasil ditambah!'); window.location='index.php?page=dosen';</script>";
    } else {
        echo "Gagal: " . mysqli_error($link);
    }
}
?>

<h2 style="font-family: Arial, sans-serif; color: #1e293b; text-align: left;">Tambah Data Dosen</h2>
<form action="" method="POST" style="font-family: Arial, sans-serif; text-align: left;">
    <table cellpadding="8" style="margin-top: 10px;">
        <tr>
            <td style="color: #475569;">NID</td>
            <td><input type="text" name="nid" required placeholder="Masukkan NID" style="padding: 6px 10px; border-radius: 4px; border: 1px solid #cbd5e1; width: 250px;"></td>
        </tr>
        <tr>
            <td style="color: #475569;">Nama Dosen</td>
            <td><input type="text" name="namadosen" required placeholder="Nama Lengkap" style="padding: 6px 10px; border-radius: 4px; border: 1px solid #cbd5e1; width: 250px;"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" name="simpan" style="background-color: #22c55e; color: white; border: none; padding: 8px 16px; cursor: pointer; border-radius: 6px; font-weight: bold;">Simpan</button>
                <a href="index.php?page=dosen" style="text-decoration: none; background-color: #64748b; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; margin-left: 5px; font-weight: bold;">Kembali</a>
            </td>
        </tr>
    </table>
</form>