<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nid = $_POST['nid'];
    $namadosen = $_POST['namadosen'];

    // Menyesuaikan kolom database: nid dan namados
    $query = "INSERT INTO tbl_dosen (nid, namados) VALUES ('$nid', '$namadosen')";
    if (mysqli_query($link, $query)) {
        // Berhasil, langsung dikembalikan ke dosen.php
        echo "<script>alert('Data berhasil ditambah!'); window.location='dosen.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($link);
    }
}
?>

<div style="background-color: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 600px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); font-family: sans-serif;">
    
    <h2 style="color: #1e293b; margin-top: 0; margin-bottom: 25px; font-size: 20px; font-weight: bold; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-plus-circle" style="color: #4f46e5; margin-right: 6px;"></i> Tambah Data Dosen Baru
    </h2>

    <form action="" method="POST">
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #475569; margin-bottom: 8px;">NID (Nomor Induk Dosen)</label>
            <input type="text" name="nid" required placeholder="Masukkan NID dosen" style="width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px; color: #334155; outline: none;" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#cbd5e1'">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #475569; margin-bottom: 8px;">Nama Lengkap Dosen</label>
            <input type="text" name="namadosen" required placeholder="Masukkan nama lengkap beserta gelar" style="width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px; color: #334155; outline: none;" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#cbd5e1'">
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" name="simpan" style="background-color: #22c55e; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#16a34a'" onmouseout="this.style.backgroundColor='#22c55e'">
                <i class="fas fa-save"></i> Simpan
            </button>
            
            <a href="dosen.php" style="text-decoration: none; background-color: #64748b; color: white; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#475569'" onmouseout="this.style.backgroundColor='#64748b'">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

    </form>
</div>