<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $nim    = $_POST['nim'];
    $tugas  = $_POST['tugas'];
    $uts    = $_POST['uts'];
    $uas    = $_POST['uas'];
    $akhir  = $_POST['akhir'];
    $hm     = $_POST['hm'];
    $status = $_POST['status'];

    // Query insert data ke tbl_nilai
    $query = "INSERT INTO tbl_nilai (nim, tugas, uts, uas, akhir, hm, status) 
              VALUES ('$nim', '$tugas', '$uts', '$uas', '$akhir', '$hm', '$status')";
              
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Data nilai berhasil ditambah!'); window.location='nilai.php';</script>";
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($link);
    }
}

// Ambil semua data mahasiswa untuk pilihan di dropdown input
$query_mhs = "SELECT nim, namamhs FROM tbl_mhs ORDER BY nim ASC";
$result_mhs = mysqli_query($link, $query_mhs);
?>

<div style="background-color: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 650px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); font-family: sans-serif;">
    
    <h2 style="color: #1e293b; margin-top: 0; margin-bottom: 25px; font-size: 20px; font-weight: bold; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-plus-circle" style="color: #4f46e5; margin-right: 6px;"></i> Input Nilai Akademik Baru
    </h2>

    <form action="" method="POST">
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #475569; margin-bottom: 8px;">Pilih Mahasiswa</label>
            <select name="nim" required style="width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px; color: #334155; background-color: #ffffff; outline: none;" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#cbd5e1'">
                <option value="">-- Pilih Mahasiswa (NIM - Nama) --</option>
                <?php while ($mhs = mysqli_fetch_assoc($result_mhs)) : ?>
                    <option value="<?= $mhs['nim']; ?>"><?= $mhs['nim']; ?> - <?= $mhs['namamhs']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 6px;">Nilai Tugas</label>
                <input type="number" id="tugas" name="tugas" min="0" max="100" required placeholder="0-100" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px;" oninput="hitungNilai()">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 6px;">Nilai UTS</label>
                <input type="number" id="uts" name="uts" min="0" max="100" required placeholder="0-100" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px;" oninput="hitungNilai()">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 6px;">Nilai UAS</label>
                <input type="number" id="uas" name="uas" min="0" max="100" required placeholder="0-100" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px;" oninput="hitungNilai()">
            </div>
        </div>

        <hr style="border: 0; border-top: 1px dashed #e2e8f0; margin: 25px 0;">

        <div style="display: flex; gap: 15px; margin-bottom: 30px;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 6px;">Nilai Akhir</label>
                <input type="text" id="akhir" name="akhir" readonly style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px; background-color: #f1f5f9; color: #334155; font-weight: bold; text-align: center;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 6px;">Huruf Mutu (HM)</label>
                <input type="text" id="hm" name="hm" readonly style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px; background-color: #f1f5f9; color: #4f46e5; font-weight: bold; text-align: center;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 13px; font-weight: bold; color: #475569; margin-bottom: 6px;">Status</label>
                <input type="text" id="status" name="status" readonly style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; font-size: 14px; background-color: #f1f5f9; font-weight: bold; text-align: center;">
            </div>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" name="simpan" style="background-color: #22c55e; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#16a34a'" onmouseout="this.style.backgroundColor='#22c55e'">
                <i class="fas fa-save"></i> Simpan Nilai
            </button>
            <a href="nilai.php" style="text-decoration: none; background-color: #64748b; color: white; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#475569'" onmouseout="this.style.backgroundColor='#64748b'">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

    </form>
</div>

<script>
function hitungNilai() {
    // Ambil nilai angka input, jika kosong default ke angka 0
    let tugas = parseFloat(document.getElementById('tugas').value) || 0;
    let uts   = parseFloat(document.getElementById('uts').value) || 0;
    let uas   = parseFloat(document.getElementById('uas').value) || 0;

    // Rumus Bobot Standar Kampus: Tugas (30%), UTS (30%), UAS (40%)
    // Silakan ganti persentase ini sesuai aturan kelompok kamu ya
    let nilaiAkhir = Math.round((tugas * 0.3) + (uts * 0.3) + (uas * 0.4));
    
    document.getElementById('akhir').value = nilaiAkhir;

    // Logika menentukan Huruf Mutu (HM) dan Status Kelulusan
    let hm = "";
    let status = "";
    let statusColor = "";

    if (nilaiAkhir >= 80) {
        hm = "A";
        status = "Lulus";
        statusColor = "#137333"; // Tulisan Hijau
    } else if (nilaiAkhir >= 70) {
        hm = "B";
        status = "Lulus";
        statusColor = "#137333";
    } else if (nilaiAkhir >= 60) {
        hm = "C";
        status = "Lulus";
        statusColor = "#137333";
    } else if (nilaiAkhir >= 50) {
        hm = "D";
        status = "Tidak Lulus";
        statusColor = "#c5221f"; // Tulisan Merah
    } else {
        hm = "E";
        status = "Tidak Lulus";
        statusColor = "#c5221f";
    }

    // Isikan hasil ke input field box
    document.getElementById('hm').value = hm;
    
    let statusInput = document.getElementById('status');
    statusInput.value = status;
    statusInput.style.color = statusColor;
}
</script>