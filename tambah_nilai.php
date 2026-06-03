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
        echo "<div class='alert error'>❌ Gagal menyimpan data: " . mysqli_error($link) . "</div>";
    }
}

// Ambil semua data mahasiswa untuk pilihan di dropdown input
$query_mhs = "SELECT nim, namamhs FROM tbl_mhs ORDER BY nim ASC";
$result_mhs = mysqli_query($link, $query_mhs);
?>

<div class="form-card">
    <div class="form-card-title">➕ Input Nilai Akademik Baru</div>
    <div class="form-card-subtitle">Pilih mahasiswa dan isi nilai untuk kalkulasi otomatis</div>

    <form action="" method="POST">

        <div class="form-section-label">👥 Pilih Mahasiswa</div>
        <div class="form-group" style="margin-bottom:14px;">
            <label>Mahasiswa <span style="color:var(--danger)">*</span></label>
            <select name="nim" required>
                <option value="">-- Pilih Mahasiswa (NIM - Nama) --</option>
                <?php while ($mhs = mysqli_fetch_assoc($result_mhs)): ?>
                    <option value="<?= htmlspecialchars($mhs['nim']) ?>">
                        <?= htmlspecialchars($mhs['nim']) ?> - <?= htmlspecialchars($mhs['namamhs']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-section-label">📝 Input Nilai</div>
        <div class="form-row-3">
            <div class="form-group">
                <label>Nilai Tugas</label>
                <input type="number" id="tugas" name="tugas" min="0" max="100" required
                    placeholder="0 – 100" oninput="hitungNilai()">
            </div>
            <div class="form-group">
                <label>Nilai UTS</label>
                <input type="number" id="uts" name="uts" min="0" max="100" required
                    placeholder="0 – 100" oninput="hitungNilai()">
            </div>
            <div class="form-group">
                <label>Nilai UAS</label>
                <input type="number" id="uas" name="uas" min="0" max="100" required
                    placeholder="0 – 100" oninput="hitungNilai()">
            </div>
        </div>

        <div class="form-section-label">🏆 Hasil Kalkulasi (otomatis)</div>
        <div class="form-row-3">
            <div class="form-group">
                <label>Nilai Akhir</label>
                <input type="text" id="akhir" name="akhir" readonly placeholder="—">
            </div>
            <div class="form-group">
                <label>Huruf Mutu (HM)</label>
                <input type="text" id="hm" name="hm" readonly placeholder="—">
            </div>
            <div class="form-group">
                <label>Status</label>
                <input type="text" id="status" name="status" readonly placeholder="—">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="simpan" class="btn btn-success">💾 Simpan Nilai</button>
            <a href="nilai.php" class="btn btn-secondary">✕ Kembali</a>
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
        hm = "A"; status = "Lulus";       statusColor = "#10b981";
    } else if (nilaiAkhir >= 70) {
        hm = "B"; status = "Lulus";       statusColor = "#10b981";
    } else if (nilaiAkhir >= 60) {
        hm = "C"; status = "Lulus";       statusColor = "#10b981";
    } else if (nilaiAkhir >= 50) {
        hm = "D"; status = "Tidak Lulus"; statusColor = "#ef4444";
    } else {
        hm = "E"; status = "Tidak Lulus"; statusColor = "#ef4444";
    }

    // Isikan hasil ke input field box
    document.getElementById('hm').value = hm;
    
    let statusInput = document.getElementById('status');
    statusInput.value = status;
    statusInput.style.color = statusColor;
    statusInput.style.fontWeight = '700';
}
</script>
