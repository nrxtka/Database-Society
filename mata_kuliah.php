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
    die("<div style='color:red;padding:10px;'>Koneksi database gagal: " . $conn->connect_error . "</div>");
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
?>
 
    <div style="flex: 1; display: flex; align-items: stretch;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; height: 100%;">
            <tr>
 
                <?php include "menu_kiri.php"; ?>
 
                <td width="80%" valign="top" style="padding: 30px; background-color: #ffffff;">
                    <h2 style="color: #1e293b; margin-top: 0; margin-bottom: 20px; font-size: 22px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                        Master Data Mata Kuliah
                    </h2>
 
                    <!-- NOTIFIKASI -->
                    <?php if ($pesan): ?>
                    <div style="
                        padding: 11px 16px; margin-bottom: 18px; border-radius: 6px; font-size: 14px;
                        background-color: <?= $tipe === 'sukses' ? '#f0fdf4' : '#fef2f2' ?>;
                        color:            <?= $tipe === 'sukses' ? '#166534' : '#991b1b' ?>;
                        border: 1px solid <?= $tipe === 'sukses' ? '#bbf7d0' : '#fecaca' ?>;">
                        <?= $tipe === 'sukses' ? '✅' : '❌' ?> <?= htmlspecialchars($pesan) ?>
                    </div>
                    <?php endif; ?>
 
                    <!-- FORM TAMBAH / EDIT -->
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:20px; margin-bottom:24px;">
                        <h3 style="margin:0 0 14px; font-size:15px; color:#334155;">
                            <?= $edit_data ? '✏️ Edit Mata Kuliah' : '➕ Tambah Mata Kuliah' ?>
                        </h3>
                        <form method="POST" action="">
                            <input type="hidden" name="aksi" value="<?= $edit_data ? 'update' : 'tambah' ?>">
                            <?php if ($edit_data): ?>
                                <input type="hidden" name="kodemk_lama" value="<?= htmlspecialchars($edit_data['kodemk']) ?>">
                            <?php endif; ?>
 
                            <div style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
                                <!-- Kode MK -->
                                <div>
                                    <label style="display:block; font-size:13px; color:#475569; margin-bottom:4px; font-weight:600;">
                                        Kode MK <span style="color:red">*</span>
                                    </label>
                                    <input type="text" name="kodemk" maxlength="10" required
                                        value="<?= $edit_data ? htmlspecialchars($edit_data['kodemk']) : '' ?>"
                                        placeholder="mis. ALGO1"
                                        style="padding:8px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px; width:130px;">
                                </div>
 
                                <!-- Nama MK -->
                                <div style="flex:1; min-width:200px;">
                                    <label style="display:block; font-size:13px; color:#475569; margin-bottom:4px; font-weight:600;">
                                        Nama Mata Kuliah <span style="color:red">*</span>
                                    </label>
                                    <input type="text" name="namamk" maxlength="30" required
                                        value="<?= $edit_data ? htmlspecialchars($edit_data['namamk']) : '' ?>"
                                        placeholder="mis. ALGORITMA DAN PEMROGRAMAN"
                                        style="padding:8px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px; width:100%; box-sizing:border-box;">
                                </div>
 
                                <!-- SKS -->
                                <div>
                                    <label style="display:block; font-size:13px; color:#475569; margin-bottom:4px; font-weight:600;">
                                        SKS <span style="color:red">*</span>
                                    </label>
                                    <input type="number" name="sks" min="1" max="6" required
                                        value="<?= $edit_data ? (int)$edit_data['sks'] : '' ?>"
                                        placeholder="1–6"
                                        style="padding:8px 10px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px; width:70px;">
                                </div>
 
                                <!-- Tombol -->
                                <div style="display:flex; gap:8px;">
                                    <button type="submit"
                                        style="padding:8px 18px; background:#2563eb; color:#fff; border:none; border-radius:6px; font-size:14px; font-weight:600; cursor:pointer; white-space:nowrap;">
                                        <?= $edit_data ? '💾 Simpan' : '➕ Tambah' ?>
                                    </button>
                                    <?php if ($edit_data): ?>
                                    <a href="mata_kuliah.php"
                                        style="padding:8px 16px; background:#64748b; color:#fff; border-radius:6px; font-size:14px; font-weight:600; text-decoration:none; white-space:nowrap;">
                                        ✖ Batal
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
 
                    <!-- TOOLBAR PENCARIAN -->
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <span style="font-size:14px; color:#64748b;">
                            Total: <strong><?= $result ? $result->num_rows : 0 ?></strong> data
                            <?= $keyword ? " — hasil pencarian \"<em>$keyword</em>\"" : "" ?>
                        </span>
                        <form method="GET" action="" style="display:flex; gap:8px;">
                            <input type="text" name="cari" value="<?= htmlspecialchars($keyword) ?>"
                                placeholder="Cari kode / nama..."
                                style="padding:7px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px; width:220px;">
                            <button type="submit"
                                style="padding:7px 14px; background:#2563eb; color:#fff; border:none; border-radius:6px; font-size:14px; cursor:pointer;">
                                🔍 Cari
                            </button>
                            <?php if ($keyword): ?>
                            <a href="mata_kuliah.php"
                                style="padding:7px 12px; background:#e2e8f0; color:#475569; border-radius:6px; font-size:14px; text-decoration:none;">
                                ✖ Reset
                            </a>
                            <?php endif; ?>
                        </form>
                    </div>
 
                    <!-- TABEL DATA -->
                    <div style="overflow-x:auto; border-radius:8px; border:1px solid #e2e8f0;">
                        <table width="100%" cellspacing="0" cellpadding="0"
                            style="border-collapse:collapse; font-size:14px;">
                            <thead>
                                <tr style="background:#1e293b; color:#fff; text-align:left;">
                                    <th style="padding:12px 14px; width:40px; text-align:center;">No</th>
                                    <th style="padding:12px 14px; width:120px;">Kode MK</th>
                                    <th style="padding:12px 14px;">Nama Mata Kuliah</th>
                                    <th style="padding:12px 14px; width:60px; text-align:center;">SKS</th>
                                    <th style="padding:12px 14px; width:140px; text-align:center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0):
                                    $no = 1;
                                    while ($row = $result->fetch_assoc()):
                                        $bg = ($no % 2 === 0) ? '#f8fafc' : '#ffffff';
                                ?>
                                <tr style="background:<?= $bg ?>; border-bottom:1px solid #e2e8f0;"
                                    onmouseover="this.style.background='#eff6ff'"
                                    onmouseout="this.style.background='<?= $bg ?>'">
                                    <td style="padding:10px 14px; text-align:center; color:#94a3b8;"><?= $no++ ?></td>
                                    <td style="padding:10px 14px; font-weight:600; color:#1e293b; font-family:monospace; font-size:13px;">
                                        <?= htmlspecialchars($row['kodemk']) ?>
                                    </td>
                                    <td style="padding:10px 14px; color:#334155;">
                                        <?= htmlspecialchars($row['namamk']) ?>
                                    </td>
                                    <td style="padding:10px 14px; text-align:center;">
                                        <span style="background:#dbeafe; color:#1d4ed8; padding:3px 12px; border-radius:12px; font-weight:700; font-size:13px;">
                                            <?= (int)$row['sks'] ?>
                                        </span>
                                    </td>
                                    <td style="padding:10px 14px; text-align:center;">
                                        <a href="mata_kuliah.php?edit=<?= urlencode($row['kodemk']) ?>"
                                            style="display:inline-block; padding:5px 12px; background:#f59e0b; color:#fff; border-radius:5px; font-size:12px; font-weight:600; text-decoration:none; margin-right:5px;">
                                            ✏️ Edit
                                        </a>
                                        <a href="mata_kuliah.php?hapus=<?= urlencode($row['kodemk']) ?>"
                                            onclick="return confirm('Yakin hapus mata kuliah <?= htmlspecialchars(addslashes($row['namamk'])) ?>?')"
                                            style="display:inline-block; padding:5px 12px; background:#ef4444; color:#fff; border-radius:5px; font-size:12px; font-weight:600; text-decoration:none;">
                                            🗑️ Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="5" style="padding:40px; text-align:center; color:#94a3b8; font-style:italic;">
                                        <?= $keyword
                                            ? "Tidak ada data yang cocok dengan pencarian \"$keyword\"."
                                            : "Belum ada data. Silakan tambahkan mata kuliah di atas." ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
 
                </td>
            </tr>
        </table>
    </div>
 
<?php
$conn->close();
include "bawah.php";
?>