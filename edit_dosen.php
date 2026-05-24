<?php
include "koneksi.php";

// Pastikan parameter NID ada di URL
if (isset($_GET['nid'])) {
    $nid_Target = $_GET['nid'];
    
    // Mengambil data dosen berdasarkan NID target
    $query = "SELECT * FROM tbl_dosen WHERE nid = '$nid_Target'";
    $result = mysqli_query($link, $query);
    $data = mysqli_fetch_assoc($result);

    // Jika data tidak ditemukan di database
    if (!$data) {
        echo "<script>alert('Data dosen tidak ditemukan!'); window.location='dosen.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('NID tidak ditentukan!'); window.location='dosen.php';</script>";
    exit;
}

if (isset($_POST['ubah'])) {
    $namadosen = $_POST['namadosen'];

    // Update data ke kolom namados sesuai database kelompok
    $update = "UPDATE tbl_dosen SET namados = '$namadosen' WHERE nid = '$nid_Target'";
    if (mysqli_query($link, $update)) {
        // PERBAIKAN: Mengarahkan kembali ke dosen.php (bukan index.php)
        echo "<script>alert('Data berhasil diubah!'); window.location='dosen.php';</script>";
    } else {
        echo "Gagal mengupdate: " . mysqli_error($link);
    }
}
?>

<div style="background-color: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 600px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); font-family: sans-serif;">
    
    <h2 style="color: #1e293b; margin-top: 0; margin-bottom: 25px; font-size: 20px; font-weight: bold; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-edit" style="color: #eab308; margin-right: 6px;"></i> Edit Data Dosen Pembimbing
    </h2>

    <form action="" method="POST">
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #475569; margin-bottom: 8px;">NID (Nomor Induk Dosen)</label>
            <input type="text" value="<?= $data['nid']; ?>" disabled style="width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px; background-color: #f1f5f9; color: #64748b; cursor: not-allowed;">
            <small style="color: #94a3b8; font-size: 12px; margin-top: 4px; display: block;">* NID tidak dapat diubah karena merupakan Primary Key.</small>
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #475569; margin-bottom: 8px;">Nama Lengkap Dosen</label>
            <input type="text" name="namadosen" value="<?= $data['namados']; ?>" required style="width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px; color: #334155; outline: none;" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#cbd5e1'">
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" name="ubah" style="background-color: #eab308; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#ca8a04'" onmouseout="this.style.backgroundColor='#eab308'">
                <i class="fas fa-sync-alt"></i> Update
            </button>
            
            <a href="dosen.php" style="text-decoration: none; background-color: #64748b; color: white; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#475569'" onmouseout="this.style.backgroundColor='#64748b'">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

    </form>
</div>