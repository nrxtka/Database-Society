<?php include "atas.php"; ?>

    <div style="flex: 1; display: flex; align-items: stretch;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; height: 100%;">
            <tr>
                
                <?php include "menu_kiri.php"; ?>
                
                <td width="80%" valign="top" style="padding: 30px; background-color: #ffffff;">
                    
                    <h2 style="color: #1e293b; margin-top: 0; margin-bottom: 20px; font-size: 22px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Master Data Dosen Pembimbing</h2>
                    
                    <div style="text-align: left; margin-bottom: 20px;">
                        <a href="?page=tambah_dosen" style="padding: 8px 14px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-block; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#3730a3'" onmouseout="this.style.backgroundColor='#4f46e5'">
                            <i class="fas fa-plus" style="margin-right: 4px;"></i> Tambah Dosen Baru
                        </a>
                    </div>

                    <div style="background-color: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.06);">
                        <table width="100%" cellspacing="0" cellpadding="12" style="border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background-color: #f8fafc; color: #4f46e5; font-weight: bold; border-bottom: 2px solid #e2e8f0;">
                                    <th style="width: 25%; padding: 15px 12px;">NID</th>
                                    <th style="padding: 15px 12px;">Nama Dosen</th>
                                    <th style="width: 20%; padding: 15px 12px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "koneksi.php";

                                // Ambil data dari tbl_dosen dan urutkan berdasarkan NID secara Ascending
                                $query = "SELECT * FROM tbl_dosen ORDER BY nid ASC";
                                $result = mysqli_query($link, $query);
                                $i = 0;

                                while ($row = mysqli_fetch_assoc($result)) : 
                                    $i++;
                                    // Membuat warna baris selang-seling (Zebra striping)
                                    $bg_row = ($i % 2 == 0) ? "#f8fafc" : "#ffffff";
                                ?>
                                <tr style="background-color: <?= $bg_row; ?>; border-bottom: 1px solid #e2e8f0; color: #334155;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='<?= $bg_row; ?>'">
                                    <td style="padding: 12px; font-weight: bold; color: #4f46e5;"><?= $row['nid']; ?></td>
                                    <td style="padding: 12px;"><?= $row['namados']; ?></td>
                                    <td style="padding: 12px; text-align: center;">
                                        <a href="?page=edit_dosen&nid=<?= $row['nid']; ?>" style="padding: 6px 12px; background-color: #eab308; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; margin-right: 5px; display: inline-block;" onmouseover="this.style.backgroundColor='#ca8a04'" onmouseout="this.style.backgroundColor='#eab308'">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?page=hapus_dosen&nid=<?= $row['nid']; ?>" style="padding: 6px 12px; background-color: #ef4444; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; display: inline-block;" onclick="return confirm('Yakin ingin menghapus data ini?')" onmouseover="this.style.backgroundColor='#dc2626'" onmouseout="this.style.backgroundColor='#ef4444'">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                
                                <?php if (mysqli_num_rows($result) == 0) : ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 20px; color: #94a3b8; font-style: italic;">Belum ada data dosen pembimbing.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </td>
            </tr>
        </table>
    </div>

<?php include "bawah.php"; ?>