<?php include "atas.php"; ?>

    <div style="flex: 1; display: flex; align-items: stretch;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; height: 100%;">
            <tr>
                
                <?php include "menu_kiri.php"; ?>
                
                <td width="80%" valign="top" style="padding: 30px; background-color: #ffffff;">
                    <h2 style="color: #1e293b; margin-top: 0; margin-bottom: 20px; font-size: 22px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">Master Data Mahasiswa</h2>
                    
                    <div style="background-color: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.06);">
                        <table width="100%" cellspacing="0" cellpadding="12" style="border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background-color: #f8fafc; color: #4f46e5; font-weight: bold; border-bottom: 2px solid #e2e8f0;">
                                    <th style="width: 8%; padding: 15px 12px;">No</th>
                                    <th style="width: 25%; padding: 15px 12px;">NIM</th>
                                    <th style="padding: 15px 12px;">Nama Mahasiswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "koneksi.php"; 

                                $query = "SELECT * FROM tbl_mhs";
                                $result = mysqli_query($link, $query);
                                $i = 0;

                                while ($data = mysqli_fetch_array($result)) {
                                    $i++;
                                    $bg_row = ($i % 2 == 0) ? "#f8fafc" : "#ffffff";
                                    
                                    echo "<tr style='background-color: $bg_row; border-bottom: 1px solid #e2e8f0;' onmouseover=\"this.style.backgroundColor='#f1f5f9'\" onmouseout=\"this.style.backgroundColor='$bg_row'\">";
                                    echo "<td style='padding: 12px; color: #64748b;'>", $i, "</td>";
                                    echo "<td style='padding: 12px; color: #4f46e5; font-weight: bold;'>", $data["nim"], "</td>";
                                    echo "<td style='padding: 12px; color: #334155;'>", $data["namamhs"], "</td>";
                                    echo "</tr>";
                                }

                                mysqli_close($link); 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

<?php include "bawah.php"; ?>