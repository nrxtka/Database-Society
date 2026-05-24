<?php include "atas.php"; ?>

    <div style="flex: 1; display: flex; align-items: stretch;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; height: 100%;">
            <tr>
                
                <?php include "menu_kiri.php"; ?>
                
                <td width="80%" align="center" valign="middle" style="padding: 20px; background-color: #ffffff;">
                    
                    <?php 
                    // Logika Dinamis untuk Memanggil Halaman Tugas Kelompok
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];

                        if ($page == 'dosen') {
                            include "dosen.php";
                        } elseif ($page == 'tambah_dosen') {
                            include "tambah_dosen.php";
                        } elseif ($page == 'edit_dosen') {
                            include "edit_dosen.php";
                        } elseif ($page == 'hapus_dosen') {
                            include "hapus_dosen.php";
                        } elseif ($page == 'querymhs') {
                            include "querymhs.php";
                        } else {
                            echo "<h3>Halaman tidak ditemukan!</h3>";
                        }
                    } else {
                        // Jika tidak ada menu yang diklik, tampilkan halaman selamat datang bawaan kelompok
                    ?>
                        <div style="background-color: #ffffff; padding: 40px 30px; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 500px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                            <img src="unidaclear.png" width="180" style="margin-bottom: 25px; filter: drop-shadow(0px 4px 10px rgba(79, 70, 229, 0.3));">
                            <h2 style="color: #1e293b; margin: 0 0 12px 0; font-size: 24px;">Selamat Datang!</h2>
                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 0;">
                                Silakan pilih menu di sebelah kiri untuk mengakses fungsionalitas sistem pengolahan database dan query data mahasiswa.
                            </p>
                        </div>
                    <?php 
                    } 
                    ?>

                </td>

            </tr>
        </table>
    </div>

<?php include "bawah.php"; ?>