<?php include "atas.php"; ?>

    <div style="flex: 1; display: flex; align-items: stretch;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; height: 100%;">
            <tr>
                
                <?php include "menu_kiri.php"; ?>
                
                <td width="80%" valign="top" style="padding: 30px; background-color: #ffffff;">
                    
                    <?php
                    $page = isset($_GET['page']) ? $_GET['page'] : 'tampil_nilai';

                    
                    if ($page == 'tambah_nilai') {
                        include "tambah_nilai.php";

                    } elseif ($page == 'edit_nilai') {
                        include "edit_nilai.php";

                   
                    } else {
                    ?>
                        <h2 style="color: #1e293b; margin-top: 0; margin-bottom: 20px; font-size: 22px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Master Data Nilai Academic</h2>
                        
                        <div style="text-align: left; margin-bottom: 20px;">
                            <a href="?page=tambah_nilai" style="padding: 8px 14px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-block; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#3730a3'" onmouseout="this.style.backgroundColor='#4f46e5'">
                                <i class="fas fa-plus" style="margin-right: 4px;"></i> Tambah Nilai Baru
                            </a>
                        </div>

                        <div style="background-color: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                            <table width="100%" cellspacing="0" cellpadding="12" style="border-collapse: collapse; text-align: left;">
                                <thead>
                                    <tr style="background-color: #f8fafc; color: #4f46e5; font-weight: bold; border-bottom: 2px solid #e2e8f0;">
                                        <th style="width: 5%; text-align: center;">No</th>
                                        <th style="width: 12%;">NIM</th>
                                        <th style="width: 20%;">Nama Mahasiswa</th>
                                        <th style="text-align: center;">Tugas</th>
                                        <th style="text-align: center;">UTS</th>
                                        <th style="text-align: center;">UAS</th>
                                        <th style="text-align: center;">Akhir</th>
                                        <th style="text-align: center;">HM</th>
                                        <th style="text-align: center; width: 12%;">Status</th>
                                        <th style="text-align: center; width: 10%;">Aksi</th> </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include "koneksi.php"; 

                                    $query = "SELECT tbl_nilai.*, tbl_mhs.namamhs 
                                              FROM tbl_nilai 
                                              INNER JOIN tbl_mhs ON tbl_nilai.nim = tbl_mhs.nim 
                                              ORDER BY tbl_nilai.nim ASC";
                                              
                                    $result = mysqli_query($link, $query);
                                    $i = 0;

                                    while ($data = mysqli_fetch_array($result)) {
                                        $i++;
                                        $bg_row = ($i % 2 == 0) ? "#f8fafc" : "#ffffff";
                                        
                                        $status_lower = strtolower($data['status']);
                                        $is_lulus = (strpos($status_lower, 'lulus') !== false && strpos($status_lower, 'tidak') === false);
                                        $badge_bg = $is_lulus ? "#e6f4ea" : "#fce8e6";
                                        $badge_color = $is_lulus ? "#137333" : "#c5221f";
                                        
                                        echo "<tr style='background-color: $bg_row; border-bottom: 1px solid #e2e8f0; color: #334155;' onmouseover=\"this.style.backgroundColor='#f1f5f9'\" onmouseout=\"this.style.backgroundColor='$bg_row'\">";
                                        echo "<td style='text-align: center; color: #64748b;'>", $i, "</td>";
                                        echo "<td style='color: #4f46e5; font-weight: bold;'>", $data["nim"], "</td>";
                                        echo "<td>", $data["namamhs"], "</td>";
                                        echo "<td style='text-align: center;'>", $data["tugas"], "</td>";
                                        echo "<td style='text-align: center;'>", $data["uts"], "</td>";
                                        echo "<td style='text-align: center;'>", $data["uas"], "</td>";
                                        echo "<td style='text-align: center; font-weight: bold; color: #1e293b;'>", $data["akhir"], "</td>";
                                        echo "<td style='text-align: center; font-weight: bold; color: #4f46e5;'>", $data["hm"], "</td>";
                                        
                                        echo "<td style='text-align: center;'>";
                                        echo "<span style='background-color: $badge_bg; color: $badge_color; padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: bold; display: inline-block;'>", $data["status"], "</span>";
                                        echo "</td>";
                                        
                                        
                                        echo "<td style='text-align: center;'>";
                                        echo "<a href='?page=edit_nilai&nim=", $data["nim"], "' style='padding: 6px 12px; background-color: #eab308; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold; display: inline-block; transition: background 0.2s;' onmouseover=\"this.style.backgroundColor='#ca8a04'\" onmouseout=\"this.style.backgroundColor='#eab308'\"><i class='fas fa-edit'></i> Edit</a>";
                                        echo "</td>";
                                        
                                        echo "</tr>";
                                    }

                                    if (mysqli_num_rows($result) == 0) {
                                        echo "<tr><td colspan='10' style='text-align: center; padding: 20px; color: #94a3b8; font-style: italic;'>Belum ada data nilai mahasiswa.</td></tr>";
                                    }

                                    mysqli_close($link); 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

                </td>
            </tr>
        </table>
    </div>

<?php include "bawah.php"; ?>