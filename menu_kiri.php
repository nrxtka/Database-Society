<?php
// Mendeteksi halaman dan parameter page yang sedang aktif
$current_page = basename($_SERVER['PHP_SELF']);
$current_action = isset($_GET['page']) ? $_GET['page'] : '';
?>
<td width="20%" valign="top" style="background-color: #f1f5f9; padding: 20px; border-right: 1px solid #e2e8f0;">
    <h3 style="color: #4f46e5; margin-top: 0; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; font-size: 16px;">Navigasi</h3>
    <ul style="list-style-type: none; padding: 0; margin: 0;">
        
        <li style="margin-bottom: 10px;">
            <a href="index.php" style="
                text-decoration: none; 
                display: block; 
                padding: 8px; 
                border-radius: 6px;
                <?php if ($current_page == 'index.php' && $current_action == '') { ?>
                    color: #ffffff; font-weight: bold; background-color: #4f46e5; border: 1px solid #3730a3;
                <?php } else { ?>
                    color: #64748b;
                <?php } ?>
            "
            <?php if ($current_page != 'index.php' || $current_action != '') { ?>
                onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.color='#4f46e5';" 
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#64748b';"
            <?php } ?>>
                🏠 Beranda
            </a>
        </li>
        
        <li style="margin-bottom: 10px;">
            <a href="index.php?page=querymhs" style="
                text-decoration: none; 
                display: block; 
                padding: 8px; 
                border-radius: 6px;
                <?php if ($current_action == 'querymhs') { ?>
                    color: #ffffff; font-weight: bold; background-color: #4f46e5; border: 1px solid #3730a3;
                <?php } else { ?>
                    color: #64748b;
                <?php } ?>
            "
            <?php if ($current_action != 'querymhs') { ?>
                onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.color='#4f46e5';" 
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#64748b';"
            <?php } ?>>
                👥 Data Mahasiswa
            </a>
        </li>

        <li style="margin-bottom: 10px;">
            <a href="index.php?page=dosen" style="
                text-decoration: none; 
                display: block; 
                padding: 8px; 
                border-radius: 6px;
                <?php if ($current_action == 'dosen' || $current_action == 'tambah_dosen' || $current_action == 'edit_dosen') { ?>
                    color: #ffffff; font-weight: bold; background-color: #4f46e5; border: 1px solid #3730a3;
                <?php } else { ?>
                    color: #64748b;
                <?php } ?>
            "
            <?php if ($current_action != 'dosen' && $current_action != 'tambah_dosen' && $current_action != 'edit_dosen') { ?>
                onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.color='#4f46e5';" 
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#64748b';"
            <?php } ?>>
                👨‍🏫 Data Dosen
            </a>
        </li>

    </ul>
</td>