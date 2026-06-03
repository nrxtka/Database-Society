<?php include "atas.php";

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
// DELETE
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['nid'])) {
    $nid = $conn->real_escape_string($_GET['nid']);
    $del = $conn->query("DELETE FROM tbl_dosen WHERE nid = '$nid'");
    $message      = $del ? "✅ Data berhasil dihapus." : "❌ Gagal menghapus: " . $conn->error;
    $message_type = $del ? "success" : "error";
}

// =============================================
// INSERT
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $nid      = $conn->real_escape_string(trim($_POST['nid']));
    $namados  = $conn->real_escape_string(trim($_POST['namados']));

    if ($nid && $namados) {
        // Cek duplikat
        $cek = $conn->query("SELECT nid FROM tbl_dosen WHERE nid = '$nid'");
        if ($cek->num_rows > 0) {
            $message      = "⚠️ NID $nid sudah terdaftar!";
            $message_type = "warning";
            $show_form    = true;
        } else {
            $ins = $conn->query("INSERT INTO tbl_dosen (nid, namados) VALUES ('$nid', '$namados')");
            $message      = $ins ? "✅ Data berhasil ditambahkan." : "❌ Gagal: " . $conn->error;
            $message_type = $ins ? "success" : "error";
            $show_form    = !$ins;
        }
    } else {
        $message      = "⚠️ Semua field wajib diisi.";
        $message_type = "warning";
        $show_form    = true;
    }
}

// =============================================
// UPDATE
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $old_nid = $conn->real_escape_string(trim($_POST['old_nid']));
    $nid     = $conn->real_escape_string(trim($_POST['nid']));
    $namados = $conn->real_escape_string(trim($_POST['namados']));

    if ($nid && $namados) {
        $upd      = $conn->query("UPDATE tbl_dosen SET nid='$nid', namados='$namados' WHERE nid='$old_nid'");
        $message  = $upd ? "✅ Data berhasil diperbarui." : "❌ Gagal update: " . $conn->error;
        $message_type = $upd ? "success" : "error";
    } else {
        $message      = "⚠️ Semua field wajib diisi.";
        $message_type = "warning";
    }
}

// =============================================
// GET DATA UNTUK EDIT
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nid'])) {
    $edit_nid = $conn->real_escape_string($_GET['nid']);
    $edit_res = $conn->query("SELECT * FROM tbl_dosen WHERE nid = '$edit_nid'");
    if ($edit_res && $edit_res->num_rows > 0) {
        $edit_data = $edit_res->fetch_assoc();
        $show_form = true;
    }
}

// =============================================
// GET DATA TABEL
// =============================================
$res_dosen = $conn->query("SELECT * FROM tbl_dosen ORDER BY nid ASC");
$dosen_data = [];
if ($res_dosen) while ($r = $res_dosen->fetch_assoc()) $dosen_data[] = $r;
$total = count($dosen_data);

// Count dopem
$dopem_count = $conn->query("SELECT COUNT(*) as c FROM tbl_dopem")->fetch_assoc()['c'];
?>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <h1>👨‍🏫 Data Dosen</h1>
        <p>Manajemen master data dosen pembimbing</p>
    </div>
    <div class="topbar-right">
        <span class="badge-count"><?= $total ?> Records</span>
        <button class="btn-add" id="btnTambah" onclick="toggleForm()">＋ Tambah Dosen</button>
    </div>
</div>

<!-- STATS -->
<div class="stats-row" style="grid-template-columns: repeat(2, 1fr); max-width:480px;">
    <div class="stat-card">
        <div class="stat-icon green">👨‍🏫</div>
        <div class="stat-body">
            <h3><?= $total ?></h3>
            <p>Total Dosen</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">🔗</div>
        <div class="stat-body">
            <h3><?= $dopem_count ?></h3>
            <p>DOPEM Aktif</p>
        </div>
    </div>
</div>

<!-- ALERT -->
<?php if ($message): ?>
    <div class="alert <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- FORM TAMBAH -->
<div id="form-tambah" class="form-card" style="display:<?= $show_form && !$edit_data ? 'block' : 'none' ?>;">
    <div class="form-card-title">✨ Tambah Data Dosen Baru</div>
    <div class="form-card-subtitle">Isi semua field di bawah untuk menambahkan dosen baru</div>

    <form method="POST" action="dosen.php">
        <input type="hidden" name="action" value="insert">

        <div class="form-row">
            <div class="form-group">
                <label>NID (Nomor Induk Dosen)</label>
                <input type="text" name="nid" placeholder="Masukkan NID dosen" required
                    value="<?= isset($_POST['nid']) && $show_form && !$edit_data ? htmlspecialchars($_POST['nid']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Nama Lengkap Dosen</label>
                <input type="text" name="namados" placeholder="Nama lengkap beserta gelar" required
                    value="<?= isset($_POST['namados']) && $show_form && !$edit_data ? htmlspecialchars($_POST['namados']) : '' ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm()">✕ Tutup</button>
        </div>
    </form>
</div>

<!-- FORM EDIT -->
<?php if ($edit_data): ?>
    <div class="form-card">
        <div class="form-card-title">✏️ Edit Data Dosen</div>
        <div class="form-card-subtitle">Ubah data yang ingin diperbaiki, lalu klik Update</div>

        <form method="POST" action="dosen.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="old_nid" value="<?= htmlspecialchars($edit_data['nid']) ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>NID (Nomor Induk Dosen)</label>
                    <input type="text" name="nid" required value="<?= htmlspecialchars($edit_data['nid']) ?>">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap Dosen</label>
                    <input type="text" name="namados" required value="<?= htmlspecialchars($edit_data['namados']) ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">🔄 Update Data</button>
                <a href="dosen.php" class="btn btn-secondary">✕ Batal</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- TABLE -->
<div class="table-card">
    <div class="table-header">
        <div>
            <h2>📋 Tabel Dosen</h2>
            <div class="sub">Master data seluruh dosen pembimbing</div>
        </div>
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari dosen..." oninput="filterTable()">
        </div>
    </div>

    <div class="table-wrap">
        <table id="dosenTable">
            <thead>
                <tr>
                    <th class="center">NO</th>
                    <th>NID</th>
                    <th>Nama Dosen</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dosen_data)): ?>
                    <tr>
                        <td colspan="4">
                            <div class="empty">
                                <div class="icon">📭</div>
                                <h3>Belum Ada Data</h3>
                                <p>Klik tombol "Tambah Dosen" untuk menambahkan data.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dosen_data as $i => $row): ?>
                        <tr>
                            <td class="td-no center"><?= $i + 1 ?></td>
                            <td><span class="badge badge-green"><?= htmlspecialchars($row['nid']) ?></span></td>
                            <td><span class="name-cell"><?= htmlspecialchars($row['namados']) ?></span></td>
                            <td class="center">
                                <div class="actions">
                                    <a href="dosen.php?action=edit&nid=<?= urlencode($row['nid']) ?>"
                                        class="btn-action btn-edit" title="Edit">✏️</a>
                                    <button class="btn-action btn-del" title="Hapus"
                                        onclick="confirmDelete('<?= htmlspecialchars($row['nid']) ?>','<?= htmlspecialchars(addslashes($row['namados'])) ?>')">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span>Menampilkan <strong><?= $total ?></strong> data dosen</span>
        <span>Basis Data 2026 — Universitas Djuanda</span>
    </div>
</div>

<!-- MODAL HAPUS -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <h3>Hapus Data?</h3>
        <p id="deleteMsg">Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal()">Batal</button>
            <a id="deleteBtn" href="#" class="btn btn-danger">Ya, Hapus</a>
        </div>
    </div>
</div>

<script>
    function toggleForm() {
        const el = document.getElementById('form-tambah');
        const btn = document.getElementById('btnTambah');
        const open = el.style.display === 'none' || el.style.display === '';
        el.style.display = open ? 'block' : 'none';
        btn.textContent = open ? '✕ Tutup Form' : '＋ Tambah Dosen';
        btn.classList.toggle('active', open);
        if (open) el.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function confirmDelete(nid, nama) {
        document.getElementById('deleteMsg').textContent =
            'Hapus dosen "' + nama + '" (NID: ' + nid + ')?';
        document.getElementById('deleteBtn').href = 'dosen.php?action=delete&nid=' + encodeURIComponent(nid);
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    const alertEl = document.querySelector('.alert');
    if (alertEl) {
        setTimeout(() => {
            alertEl.style.transition = 'opacity .5s';
            alertEl.style.opacity = '0';
            setTimeout(() => alertEl.remove(), 500);
        }, 4000);
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#dosenTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    <?php if ($edit_data): ?>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.form-card')?.scrollIntoView({
                behavior: 'smooth'
            });
        });
    <?php endif; ?>
</script>

<?php $conn->close(); ?>

<?php include "bawah.php"; ?>
