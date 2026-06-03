<?php 
include "atas.php";

// =============================================
// KONFIGURASI DATABASE
// =============================================
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "basisdata2026";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("<div style='padding:20px;color:red;font-family:sans-serif;'>❌ Koneksi gagal: " . $conn->connect_error . "</div>");
}

$message      = "";
$message_type = "";
$show_form    = false;
$edit_data    = null;

// =============================================
// SHOW FORM (ACTION=ADD)
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    $show_form = true;
}

// =============================================
// DELETE
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['nim'])) {
    $nim = $conn->real_escape_string($_GET['nim']);
    $del = $conn->query("DELETE FROM tbl_nilai WHERE nim = '$nim'");
    $message      = $del ? "✅ Data berhasil dihapus." : "❌ Gagal menghapus: " . $conn->error;
    $message_type = $del ? "success" : "error";
}

// =============================================
// EDIT
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nim'])) {
    $edit_nim = $conn->real_escape_string($_GET['nim']);
    $edit_res = $conn->query("
        SELECT n.*, m.namamhs 
        FROM tbl_nilai n 
        LEFT JOIN tbl_mhs m ON n.nim = m.nim 
        WHERE n.nim = '$edit_nim'
    ");
    if ($edit_res && $edit_res->num_rows > 0) {
        $edit_data = $edit_res->fetch_assoc();
        $show_form = true;
    }
}

// =============================================
// INSERT / UPDATE
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $nim   = $conn->real_escape_string(trim($_POST['nim']));
    $tugas = isset($_POST['tugas']) ? intval($_POST['tugas']) : 0;
    $uts   = isset($_POST['uts']) ? intval($_POST['uts']) : 0;
    $uas   = isset($_POST['uas']) ? intval($_POST['uas']) : 0;
    $akhir = isset($_POST['akhir']) ? intval($_POST['akhir']) : 0;
    
    if ($nim && ($tugas || $uts || $uas || $akhir)) {
        if ($_POST['action'] === 'insert') {
            // Cek duplikat
            $cek = $conn->query("SELECT nim FROM tbl_nilai WHERE nim = '$nim'");
            if ($cek->num_rows > 0) {
                $message      = "❌ NIM sudah terdaftar!";
                $message_type = "error";
            } else {
                $ins = $conn->query("INSERT INTO tbl_nilai (nim, tugas, uts, uas, akhir) VALUES ('$nim', $tugas, $uts, $uas, $akhir)");
                $message      = $ins ? "✅ Data berhasil ditambahkan." : "❌ Gagal: " . $conn->error;
                $message_type = $ins ? "success" : "error";
                if ($ins) {
                    $_POST['nim'] = '';
                    $_POST['tugas'] = '';
                    $_POST['uts'] = '';
                    $_POST['uas'] = '';
                    $_POST['akhir'] = '';
                    $show_form = false;
                }
            }
        } elseif ($_POST['action'] === 'update') {
            $old_nim = $conn->real_escape_string(trim($_POST['old_nim']));
            $upd = $conn->query("UPDATE tbl_nilai SET tugas=$tugas, uts=$uts, uas=$uas, akhir=$akhir WHERE nim = '$old_nim'");
            $message      = $upd ? "✅ Data berhasil diupdate." : "❌ Gagal: " . $conn->error;
            $message_type = $upd ? "success" : "error";
            if ($upd) {
                $edit_data = null;
                $show_form = false;
            }
        }
    } else {
        $message      = "❌ NIM dan minimal satu nilai harus diisi!";
        $message_type = "error";
        $show_form    = true;
    }
}

// =============================================
// READ DATA
// =============================================
$res_nilai = $conn->query("
    SELECT n.nim, m.namamhs, n.tugas, n.uts, n.uas, n.akhir,
           ROUND((n.tugas + n.uts + n.uas + n.akhir) / 4, 2) as rata_rata
    FROM tbl_nilai n
    LEFT JOIN tbl_mhs m ON n.nim = m.nim
    ORDER BY n.nim ASC
");
$nilai_data = [];
if ($res_nilai) while ($r = $res_nilai->fetch_assoc()) $nilai_data[] = $r;

$total = count($nilai_data);
$total_lulus = 0;
$total_tidak = 0;
foreach ($nilai_data as $row) {
    if ($row['akhir'] >= 60) $total_lulus++;
    else $total_tidak++;
}

// Get list of mahasiswa for dropdown
$res_mhs = $conn->query("SELECT nim, namamhs FROM tbl_mhs ORDER BY nim ASC");
$mhs_list = [];
if ($res_mhs) while ($r = $res_mhs->fetch_assoc()) $mhs_list[] = $r;
?>

<div class="topbar">
    <div class="topbar-left">
        <h1>💯 Nilai Akademik</h1>
        <p>Manajemen data nilai akademik mahasiswa</p>
    </div>
    <div class="topbar-right">
        <span class="badge-count"><?= $total ?> Records</span>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<?php if ($show_form): ?>
    <div class="form-card">
        <h2><?= $edit_data ? "✏️ Edit Nilai" : "➕ Tambah Nilai Baru" ?></h2>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $edit_data ? 'update' : 'insert' ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="old_nim" value="<?= htmlspecialchars($edit_data['nim']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>NIM Mahasiswa</label>
                <select name="nim" required <?= $edit_data ? 'disabled' : '' ?>>
                    <option value="">-- Pilih Mahasiswa --</option>
                    <?php foreach ($mhs_list as $mhs): ?>
                        <option value="<?= $mhs['nim'] ?>" 
                            <?= (isset($_POST['nim']) && $_POST['nim'] == $mhs['nim']) || (isset($edit_data['nim']) && $edit_data['nim'] == $mhs['nim']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($mhs['nim'] . ' - ' . $mhs['namamhs']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($edit_data): ?>
                    <input type="hidden" name="nim" value="<?= htmlspecialchars($edit_data['nim']) ?>">
                <?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nilai Tugas</label>
                    <input type="number" name="tugas" min="0" max="100" placeholder="0-100"
                        value="<?= isset($_POST['tugas']) && $show_form && !$edit_data ? htmlspecialchars($_POST['tugas']) : (isset($edit_data['tugas']) ? htmlspecialchars($edit_data['tugas']) : '') ?>">
                </div>
                <div class="form-group">
                    <label>Nilai UTS</label>
                    <input type="number" name="uts" min="0" max="100" placeholder="0-100"
                        value="<?= isset($_POST['uts']) && $show_form && !$edit_data ? htmlspecialchars($_POST['uts']) : (isset($edit_data['uts']) ? htmlspecialchars($edit_data['uts']) : '') ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Nilai UAS</label>
                    <input type="number" name="uas" min="0" max="100" placeholder="0-100"
                        value="<?= isset($_POST['uas']) && $show_form && !$edit_data ? htmlspecialchars($_POST['uas']) : (isset($edit_data['uas']) ? htmlspecialchars($edit_data['uas']) : '') ?>">
                </div>
                <div class="form-group">
                    <label>Nilai Akhir</label>
                    <input type="number" name="akhir" min="0" max="100" placeholder="0-100"
                        value="<?= isset($_POST['akhir']) && $show_form && !$edit_data ? htmlspecialchars($_POST['akhir']) : (isset($edit_data['akhir']) ? htmlspecialchars($edit_data['akhir']) : '') ?>">
                </div>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn btn-success">💾 Simpan</button>
                <a href="nilai.php" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="form-card" style="margin-bottom: 20px; text-align: right;">
        <a href="?action=add" class="btn btn-primary">➕ Tambah Nilai</a>
    </div>
<?php endif; ?>

<!-- STATS -->
<div class="stats-row" style="grid-template-columns: repeat(3,1fr); max-width:560px;">
    <div class="stat-card">
        <div class="stat-icon rose">💯</div>
        <div class="stat-body"><h3><?= $total ?></h3><p>Total Nilai</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div class="stat-body"><h3><?= $total_lulus ?></h3><p>Lulus (≥60)</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">❌</div>
        <div class="stat-body"><h3><?= $total_tidak ?></h3><p>Tidak Lulus (<60)</p></div>
    </div>
</div>

<!-- TABLE -->
<div class="table-card">
    <div class="table-header">
        <div>
            <h2>📋 Tabel Nilai Akademik</h2>
            <div class="sub">Data nilai mahasiswa — Tugas, UTS, UAS, dan Nilai Akhir</div>
        </div>
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari mahasiswa..." oninput="filterTable()">
        </div>
    </div>

    <div class="table-wrap">
        <table id="nilaiTable">
            <thead>
                <tr>
                    <th class="center">NO</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th class="center">Tugas</th>
                    <th class="center">UTS</th>
                    <th class="center">UAS</th>
                    <th class="center">Akhir</th>
                    <th class="center">Rata-Rata</th>
                    <th class="center">Status</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($total > 0) {
                    foreach ($nilai_data as $idx => $row) {
                        $status = $row['akhir'] >= 60 ? '✅ Lulus' : '❌ Tidak Lulus';
                        $status_color = $row['akhir'] >= 60 ? '#28a745' : '#dc3545';
                        
                        echo "<tr>";
                        echo "<td class='center'>" . ($idx + 1) . "</td>";
                        echo "<td><strong>" . htmlspecialchars($row['nim']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row['namamhs'] ?? '—') . "</td>";
                        echo "<td class='center'>" . htmlspecialchars($row['tugas']) . "</td>";
                        echo "<td class='center'>" . htmlspecialchars($row['uts']) . "</td>";
                        echo "<td class='center'>" . htmlspecialchars($row['uas']) . "</td>";
                        echo "<td class='center'><strong>" . htmlspecialchars($row['akhir']) . "</strong></td>";
                        echo "<td class='center'>" . htmlspecialchars($row['rata_rata']) . "</td>";
                        echo "<td class='center' style='color: $status_color; font-weight: 600;'>$status</td>";
                        echo "<td class='center'>";
                        echo "<a href='?action=edit&nim=" . urlencode($row['nim']) . "' class='btn-icon' title='Edit'>✏️</a> ";
                        echo "<a href='?action=delete&nim=" . urlencode($row['nim']) . "' class='btn-icon' title='Hapus' onclick='return confirm(\"Yakin hapus?\")'>🗑️</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='10'>";
                    echo "<div class='empty'>";
                    echo "<div class='icon'>📭</div>";
                    echo "<h3>Belum Ada Data Nilai</h3>";
                    echo "<p>Klik tombol 'Tambah Nilai' untuk menambahkan data nilai mahasiswa.</p>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span>Menampilkan <strong><?= $total ?></strong> data nilai</span>
        <span>Basis Data 2026 — Universitas Djuanda</span>
    </div>
</div>

<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#nilaiTable tbody tr').forEach(row => {
        const isVisible = row.textContent.toLowerCase().includes(q);
        row.style.display = isVisible ? '' : 'none';
    });
}
</script>

<style>
.alert {
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-weight: 500;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.form-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.form-card h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #555;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: Arial, sans-serif;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.form-group input:disabled,
.form-group select:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
}

.form-buttons {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    text-align: center;
    transition: all 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,86,179,0.3);
}

.btn-success {
    background-color: #28a745;
    color: white;
    flex: 1;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    flex: 1;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.btn-icon {
    display: inline-block;
    font-size: 18px;
    text-decoration: none;
    padding: 5px 8px;
    border-radius: 4px;
    transition: all 0.3s;
}

.btn-icon:hover {
    transform: scale(1.2);
    background-color: #f0f0f0;
}

.empty {
    text-align: center;
    padding: 40px 20px;
}

.empty .icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.empty h3 {
    margin: 10px 0;
    color: #999;
}

.empty p {
    color: #bbb;
}

.table-footer {
    display: flex;
    justify-content: space-between;
    padding: 15px 20px;
    background-color: #f9f9f9;
    border-top: 1px solid #eee;
    font-size: 13px;
    color: #666;
}

.badge-count {
    display: inline-block;
    padding: 6px 12px;
    background-color: #e9ecef;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    color: #495057;
}

.center {
    text-align: center;
}
</style>

<?php include "bawah.php"; ?>
