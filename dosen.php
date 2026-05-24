<?php
// Include file koneksi dari kelompok
include "koneksi.php";

// Ambil data dari tbl_dosen dan urutkan berdasarkan NID secara Ascending (terkecil ke terbesar)
$query = "SELECT * FROM tbl_dosen ORDER BY nid ASC";
$result = mysqli_query($link, $query);
?>

<h2 style="margin-bottom: 20px; font-family: Arial, sans-serif; text-align: left; color: #1e293b;">Daftar Data Dosen</h2>

<div style="text-align: left; margin-bottom: 20px;">
    <a href="?page=tambah_dosen" style="padding: 8px 14px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 6px; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold;">+ Tambah Dosen Baru</a>
</div>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; border: 1px solid #e2e8f0;">
    <thead>
        <tr style="background-color: #f1f5f9; color: #475569;">
            <th style="text-align: left; border: 1px solid #e2e8f0;">NID</th>
            <th style="text-align: left; border: 1px solid #e2e8f0;">Nama Dosen</th>
            <th style="text-align: left; width: 150px; border: 1px solid #e2e8f0;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr style="color: #334155;">
            <td style="border: 1px solid #e2e8f0; text-align: left;"><?= $row['nid']; ?></td>
            <td style="border: 1px solid #e2e8f0; text-align: left;"><?= $row['namados']; ?></td> <td style="border: 1px solid #e2e8f0; text-align: left;">
                <a href="?page=edit_dosen&nid=<?= $row['nid']; ?>" style="padding: 4px 10px; background-color: #eab308; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; margin-right: 5px;">Edit</a>
                <a href="?page=hapus_dosen&nid=<?= $row['nid']; ?>" style="padding: 4px 10px; background-color: #ef4444; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold;" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
