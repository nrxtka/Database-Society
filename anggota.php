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
    $del = $conn->query("DELETE FROM tbl_anggota WHERE NIM = '$nim'");
    $message      = $del ? "✅ Data berhasil dihapus." : "❌ Gagal menghapus: " . $conn->error;
    $message_type = $del ? "success" : "error";
}

// =============================================
// EDIT
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nim'])) {
    $edit_nim = $conn->real_escape_string($_GET['nim']);
    $edit_res = $conn->query("SELECT * FROM tbl_anggota WHERE NIM = '$edit_nim'");
    if ($edit_res && $edit_res->num_rows > 0) {
        $edit_data = $edit_res->fetch_assoc();
        $show_form = true;
    }
}

// =============================================
// INSERT / UPDATE
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $nim      = $conn->real_escape_string(trim($_POST['nim']));
    
    if ($username && $nim) {
        if ($_POST['action'] === 'insert') {
            // Cek duplikat
            $cek = $conn->query("SELECT NIM FROM tbl_anggota WHERE NIM = '$nim'");
            if ($cek->num_rows > 0) {
                $message      = "❌ NIM sudah terdaftar!";
                $message_type = "error";
            } else {
                $ins = $conn->query("INSERT INTO tbl_anggota (Username, NIM) VALUES ('$username', '$nim')");
                $message      = $ins ? "✅ Data berhasil ditambahkan." : "❌ Gagal: " . $conn->error;
                $message_type = $ins ? "success" : "error";
                if ($ins) {
                    $_POST['username'] = '';
                    $_POST['nim'] = '';
                }
            }
        } elseif ($_POST['action'] === 'update') {
            $old_nim = $conn->real_escape_string(trim($_POST['old_nim']));
            $upd = $conn->query("UPDATE tbl_anggota SET Username = '$username', NIM = '$nim' WHERE NIM = '$old_nim'");
            $message      = $upd ? "✅ Data berhasil diupdate." : "❌ Gagal: " . $conn->error;
            $message_type = $upd ? "success" : "error";
            if ($upd) {
                $edit_data = null;
                $show_form = false;
            }
        }
    } else {
        $message      = "❌ Semua field harus diisi!";
        $message_type = "error";
        $show_form    = true;
    }
}

// =============================================
// READ DATA
// =============================================
$res_anggota = $conn->query("SELECT * FROM tbl_anggota ORDER BY NIM ASC");
$anggota_data = [];
if ($res_anggota) while ($r = $res_anggota->fetch_assoc()) $anggota_data[] = $r;
$total = count($anggota_data);
?>

<div class="topbar">
    <div class="topbar-left">
        <h1>🪪 Anggota Kelompok</h1>
        <p>Data anggota kelompok pengembang sistem</p>
    </div>
    <div class="topbar-right">
        <span class="badge-count"><?= $total ?> Anggota</span>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<?php if ($show_form): ?>
    <div class="form-card">
        <h2><?= $edit_data ? "✏️ Edit Anggota" : "➕ Tambah Anggota Baru" ?></h2>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $edit_data ? 'update' : 'insert' ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="old_nim" value="<?= htmlspecialchars($edit_data['NIM']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nama Anggota</label>
                <input type="text" name="username" placeholder="Contoh: Muhammad Zibril" required
                    value="<?= isset($_POST['username']) && $show_form ? htmlspecialchars($_POST['username']) : (isset($edit_data['Username']) ? htmlspecialchars($edit_data['Username']) : '') ?>">
            </div>
            
            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="nim" placeholder="Contoh: I.2510056" required
                    value="<?= isset($_POST['nim']) && $show_form && !$edit_data ? htmlspecialchars($_POST['nim']) : (isset($edit_data['NIM']) ? htmlspecialchars($edit_data['NIM']) : '') ?>"
                    <?= $edit_data ? 'readonly' : '' ?>>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn btn-success">💾 Simpan</button>
                <a href="anggota.php" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="form-card" style="margin-bottom: 20px; text-align: right;">
        <a href="?action=add" class="btn btn-primary">➕ Tambah Anggota</a>
    </div>
<?php endif; ?>

<div class="table-card">
    <div class="table-header">
        <div>
            <h2>📋 Tabel Anggota</h2>
            <div class="sub">Daftar anggota kelompok basis data 2026</div>
        </div>
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari anggota..." oninput="filterTable()">
        </div>
    </div>

    <div class="table-wrap">
        <table id="anggotaTable">
            <thead>
                <tr>
                    <th class="center">NO</th>
                    <th>NIM</th>
                    <th>Nama Anggota</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($total > 0) {
                    foreach ($anggota_data as $idx => $row) {
                        echo "<tr>";
                        echo "<td class='center'>" . ($idx + 1) . "</td>";
                        echo "<td><strong>" . htmlspecialchars($row['NIM']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                        echo "<td class='center'>";
                        echo "<a href='?action=edit&nim=" . urlencode($row['NIM']) . "' class='btn-icon' title='Edit'>✏️</a> ";
                        echo "<a href='?action=delete&nim=" . urlencode($row['NIM']) . "' class='btn-icon' title='Hapus' onclick='return confirm(\"Yakin hapus?\")'>🗑️</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='4'>";
                    echo "<div class='empty'>";
                    echo "<div class='icon'>🪪</div>";
                    echo "<h3>Belum Ada Data</h3>";
                    echo "<p>Data anggota kelompok akan ditampilkan di sini.</p>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span>Total: <?= $total ?> Anggota</span>
        <span>Basis Data 2026 — Universitas Djuanda</span>
    </div>
</div>
<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#anggotaTable tbody tr').forEach(row => {
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

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #555;
}

.form-group input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: Arial, sans-serif;
    box-sizing: border-box;
}

.form-group input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.form-group input:readonly {
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
</style>

<?php include "bawah.php"; ?>
