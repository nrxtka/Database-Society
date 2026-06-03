<?php include "atas.php"; ?>
 
<?php
// =============================================
// KONEKSI DATABASE
// =============================================
$host     = "localhost";
$user     = "root";
$password = "";
$dbname   = "basisdata2026"; // Sesuaikan nama database
 
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("<div class='alert error'>❌ Koneksi database gagal: " . $conn->connect_error . "</div>");
}
$conn->set_charset("utf8");
 
// =============================================
// PROSES CRUD
// =============================================
$pesan     = "";
$tipe      = "";
$edit_data = null;
 
// --- TAMBAH ---
if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $kodemk = $conn->real_escape_string(trim($_POST['kodemk']));
    $namamk = $conn->real_escape_string(trim($_POST['namamk']));
    $sks    = (int)$_POST['sks'];
 
    if ($kodemk && $namamk && $sks > 0) {
        $sql = "INSERT INTO tbl_matakuliah (kodemk, namamk, sks) VALUES ('$kodemk','$namamk',$sks)";
        if ($conn->query($sql)) {
            $pesan = "Data mata kuliah berhasil ditambahkan.";
            $tipe  = "sukses";
        } else {
            $pesan = "Gagal menambahkan: " . $conn->error;
            $tipe  = "gagal";
        }
    } else {
        $pesan = "Semua field wajib diisi.";
        $tipe  = "gagal";
    }
}
 
// --- EDIT (load form) ---
if (isset($_GET['edit'])) {
    $kodemk_edit = $conn->real_escape_string($_GET['edit']);
    $res = $conn->query("SELECT * FROM tbl_matakuliah WHERE kodemk='$kodemk_edit'");
    if ($res && $res->num_rows > 0) {
        $edit_data = $res->fetch_assoc();
    }
}
 
// --- UPDATE ---
if (isset($_POST['aksi']) && $_POST['aksi'] === 'update') {
    $kodemk_lama = $conn->real_escape_string(trim($_POST['kodemk_lama']));
    $kodemk      = $conn->real_escape_string(trim($_POST['kodemk']));
    $namamk      = $conn->real_escape_string(trim($_POST['namamk']));
    $sks         = (int)$_POST['sks'];
 
    if ($kodemk && $namamk && $sks > 0) {
        $sql = "UPDATE tbl_matakuliah SET kodemk='$kodemk', namamk='$namamk', sks=$sks WHERE kodemk='$kodemk_lama'";
        if ($conn->query($sql)) {
            $pesan = "Data mata kuliah berhasil diperbarui.";
            $tipe  = "sukses";
        } else {
            $pesan = "Gagal memperbarui: " . $conn->error;
            $tipe  = "gagal";
        }
    } else {
        $pesan = "Semua field wajib diisi.";
        $tipe  = "gagal";
    }
}
 
// --- HAPUS ---
if (isset($_GET['hapus'])) {
    $kodemk_hapus = $conn->real_escape_string($_GET['hapus']);
    if ($conn->query("DELETE FROM tbl_matakuliah WHERE kodemk='$kodemk_hapus'")) {
        $pesan = "Data mata kuliah berhasil dihapus.";
        $tipe  = "sukses";
    } else {
        $pesan = "Gagal menghapus: " . $conn->error;
        $tipe  = "gagal";
    }
}
 
// --- AMBIL DATA ---
$keyword = isset($_GET['cari']) ? $conn->real_escape_string(trim($_GET['cari'])) : "";
$where   = $keyword
    ? "WHERE kodemk LIKE '%$keyword%' OR namamk LIKE '%$keyword%'"
    : "";
$result = $conn->query("SELECT * FROM tbl_matakuliah $where ORDER BY kodemk ASC");
$total  = $result ? $result->num_rows : 0;
?>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <h1>📚 Mata Kuliah</h1>
        <p>Manajemen master data mata kuliah</p>
    </div>
    <div class="topbar-right">
        <span class="badge-count"><?= $total ?> Mata Kuliah</span>
    </div>
</div>

<!-- STATS -->
<div class="stats-row" style="grid-template-columns: repeat(2,1fr); max-width:400px;">
    <div class="stat-card">
        <div class="stat-icon amber">📚</div>
        <div class="stat-body">
            <h3><?= $conn->query("SELECT COUNT(*) as c FROM tbl_matakuliah")->fetch_assoc()['c'] ?? 0 ?></h3>
            <p>Total Mata Kuliah</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">📊</div>
        <div class="stat-body">
            <?php
            $sks_res = $conn->query("SELECT SUM(sks) as s FROM tbl_matakuliah");
            $total_sks = $sks_res ? ($sks_res->fetch_assoc()['s'] ?? 0) : 0;
            ?>
            <h3><?= $total_sks ?></h3>
            <p>Total SKS</p>
        </div>
    </div>
</div>

<!-- ALERT -->
<?php if ($pesan): ?>
<div class="alert <?= $tipe === 'sukses' ? 'success' : 'error' ?>">
    <?= $tipe === 'sukses' ? '✅' : '❌' ?> <?= htmlspecialchars($pesan) ?>
</div>
<?php endif; ?>

<!-- FORM TAMBAH / EDIT -->
<div class="form-card">
    <div class="form-card-title">
        <?= $edit_data ? '✏️ Edit Mata Kuliah' : '➕ Tambah Mata Kuliah' ?>
    </div>
    <div class="form-card-subtitle">
        <?= $edit_data ? 'Ubah data mata kuliah yang ingin diperbaiki' : 'Isi form di bawah untuk menambahkan mata kuliah baru' ?>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="aksi" value="<?= $edit_data ? 'update' : 'tambah' ?>">
        <?php if ($edit_data): ?>
            <input type="hidden" name="kodemk_lama" value="<?= htmlspecialchars($edit_data['kodemk']) ?>">
        <?php endif; ?>

        <div class="form-row-3">
            <div class="form-group">
                <label>Kode MK <span style="color:var(--danger)">*</span></label>
                <input type="text" name="kodemk" maxlength="10" required
                    value="<?= $edit_data ? htmlspecialchars($edit_data['kodemk']) : '' ?>"
                    placeholder="mis. ALGO1">
            </div>
            <div class="form-group">
                <label>Nama Mata Kuliah <span style="color:var(--danger)">*</span></label>
                <input type="text" name="namamk" maxlength="30" required
                    value="<?= $edit_data ? htmlspecialchars($edit_data['namamk']) : '' ?>"
                    placeholder="mis. ALGORITMA DAN PEMROGRAMAN">
            </div>
            <div class="form-group">
                <label>SKS <span style="color:var(--danger)">*</span></label>
                <input type="number" name="sks" min="1" max="6" required
                    value="<?= $edit_data ? (int)$edit_data['sks'] : '' ?>"
                    placeholder="1–6">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $edit_data ? '💾 Simpan Perubahan' : '➕ Tambah' ?>
            </button>
            <?php if ($edit_data): ?>
            <a href="mata_kuliah.php" class="btn btn-secondary">✕ Batal</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- TOOLBAR PENCARIAN -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px;">
    <span style="font-size:14px; color:var(--text-2);">
        Total: <strong><?= $total ?></strong> data
        <?= $keyword ? " — hasil pencarian \"<em>$keyword</em>\"" : "" ?>
    </span>
    <form method="GET" action="" style="display:flex; gap:8px;">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" name="cari" value="<?= htmlspecialchars($keyword) ?>"
                placeholder="Cari kode / nama...">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
        <?php if ($keyword): ?>
        <a href="mata_kuliah.php" class="btn btn-secondary btn-sm">✕ Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- TABEL -->
<div class="table-card">
    <div class="table-header">
        <div>
            <h2>📋 Tabel Mata Kuliah</h2>
            <div class="sub">Daftar seluruh mata kuliah yang tersedia</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th class="center">NO</th>
                    <th>Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th class="center">SKS</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0):
                    $no = 1;
                    while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td class="td-no center"><?= $no++ ?></td>
                    <td><span class="badge badge-amber"><?= htmlspecialchars($row['kodemk']) ?></span></td>
                    <td><span class="name-cell"><?= htmlspecialchars($row['namamk']) ?></span></td>
                    <td class="center">
                        <span class="badge badge-blue"><?= (int)$row['sks'] ?> SKS</span>
                    </td>
                    <td class="center">
                        <div class="actions">
                            <a href="mata_kuliah.php?edit=<?= urlencode($row['kodemk']) ?>"
                               class="btn-action btn-edit" title="Edit">✏️</a>
                            <a href="mata_kuliah.php?hapus=<?= urlencode($row['kodemk']) ?>"
                               class="btn-action btn-del" title="Hapus"
                               onclick="return confirm('Yakin hapus mata kuliah <?= htmlspecialchars(addslashes($row['namamk'])) ?>?')">🗑️</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="5">
                        <div class="empty">
                            <div class="icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p><?= $keyword ? "Tidak ada data yang cocok dengan pencarian \"$keyword\"." : "Silakan tambahkan mata kuliah di form atas." ?></p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span>Menampilkan <strong><?= $total ?></strong> mata kuliah</span>
        <span>Basis Data 2026 — Universitas Djuanda</span>
    </div>
</div>

<?php
$conn->close();
include "bawah.php";
?>
