<?php
include "koneksi.php";

$nid_Target = $_GET['nid'];
// Mengambil data dosen berdasarkan NID target
$query = "SELECT * FROM tbl_dosen WHERE nid = '$nid_Target'";
$result = mysqli_query($link, $query);
$data = mysqli_fetch_assoc($result);

if (isset($_POST['ubah'])) {
    $namadosen = $_POST['namadosen'];

    // Update data ke kolom namados sesuai database kelompok
    $update = "UPDATE tbl_dosen SET namados = '$namadosen' WHERE nid = '$nid_Target'";
    if (mysqli_query($link, $update)) {
        echo "<script>alert('Data berhasil diubah!'); window.location='index.php?page=dosen';</script>";
    } else {
        echo "Gagal mengupdate: " . mysqli_error($link);
    }
}
?>

<h2 style="font-family: Arial, sans-serif; color: #1e293b; text-align: left;">Edit Data Dosen</h2>
<form action="" method="POST" style="font-family: Arial, sans-serif; text-align: left;">
    <table cellpadding="8" style="margin-top: 10px;">
        <tr>
            <td style="color: #475569;">NID</td>
            <td><input type="text" name="nid" value="<?= $data['nid']; ?>" disabled style="background-color: #f1f5f9; padding: 6px 10px; border-radius: 4px; border: 1px solid #cbd5e1; width: 250px; color: #64748b;"></td>
        </tr>
        <tr>
            <td style="color: #475569;">Nama Dosen</td>
            <td><input type="text" name="namadosen" value="<?= $data['namados']; ?>" required style="padding: 6px 10px; border-radius: 4px; border: 1px solid #cbd5e1; width: 250px;"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" name="ubah" style="background-color: #eab308; color: white; border: none; padding: 8px 16px; cursor: pointer; border-radius: 6px; font-weight: bold;">Update</button>
                <a href="index.php?page=dosen" style="text-decoration: none; background-color: #64748b; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; margin-left: 5px; font-weight: bold;">Batal</a>
            </td>
        </tr>
    </table>
</form>