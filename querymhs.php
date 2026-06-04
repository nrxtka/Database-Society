<?php include "atas.php";

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
// DELETE MAHASISWA
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['nim'])) {
    $nim = $conn->real_escape_string($_GET['nim']);
    $del = $conn->query("DELETE FROM tbl_mhs WHERE nim = '$nim'");
    $message      = $del ? "✅ Data mahasiswa berhasil dihapus." : "❌ Gagal menghapus: " . $conn->error;
    $message_type = $del ? "success" : "error";
}

// =============================================
// INSERT MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $nim      = $conn->real_escape_string(trim($_POST['nim']));
    $namamhs  = $conn->real_escape_string(trim($_POST['namamhs']));

    if ($nim && $namamhs) {
        // Cek duplikat NIM
        $cek = $conn->query("SELECT nim FROM tbl_mhs WHERE nim = '$nim'");
        if ($cek->num_rows > 0) {
            $message      = "⚠️ NIM $nim sudah terdaftar!";
            $message_type = "warning";
            $show_form    = true;
        } else {
            $ins = $conn->query("INSERT INTO tbl_mhs (nim, namamhs) VALUES ('$nim', '$namamhs')");
            $message      = $ins ? "✅ Data mahasiswa berhasil ditambahkan." : "❌ Gagal: " . $conn->error;
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
// UPDATE MAHASISWA
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $old_nim = $conn->real_escape_string(trim($_POST['old_nim']));
    $nim     = $conn->real_escape_string(trim($_POST['nim']));
    $namamhs = $conn->real_escape_string(trim($_POST['namamhs']));

    if ($nim && $namamhs) {
        $upd      = $conn->query("UPDATE tbl_mhs SET nim='$nim', namamhs='$namamhs' WHERE nim='$old_nim'");
        $message  = $upd ? "✅ Data mahasiswa berhasil diperbarui." : "❌ Gagal update: " . $conn->error;
        $message_type = $upd ? "success" : "error";
    } else {
        $message      = "⚠️ Semua field wajib diisi.";
        $message_type = "warning";
    }
}

// =============================================
// GET DATA UNTUK EDIT
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nim'])) {
    $edit_nim = $conn->real_escape_string($_GET['nim']);
    $edit_res = $conn->query("SELECT * FROM tbl_mhs WHERE nim = '$edit_nim'");
    if ($edit_res && $edit_res->num_rows > 0) {
        $edit_data = $edit_res->fetch_assoc();
        $show_form = true;
    }
}

// =============================================
// GET DATA TABEL
// =============================================
$res_mhs = $conn->query("SELECT * FROM tbl_mhs ORDER BY nim ASC");
$mhs_data = [];
if ($res_mhs) while ($r = $res_mhs->fetch_assoc()) $mhs_data[] = $r;
$total = count($mhs_data);

// Mengambil count data nilai
$nil_res = $conn->query("SELECT COUNT(*) as c FROM tbl_nilai");
$total_nil = $nil_res ? $nil_res->fetch_assoc()['c'] : 0;
?>

<div class="topbar">
    <div class="topbar-left">
        <h1>👥 Data Mahasiswa</h1>
        <p>Master data seluruh mahasiswa terdaftar</p>
    </div>
    <div class="topbar-right">
        <span class="badge-count"><?= $total ?> Records</span>
        <button class="btn-add" id="btnTambah" onclick="toggleForm()">＋ Tambah Mahasiswa</button>
    </div>
</div>

<div class="stats-row" style="grid-template-columns: repeat(2, 1fr); max-width:400px;">
    <div class="stat-card">
        <div class="stat-icon cyan">👥</div>
        <div class="stat-body">
            <h3><?= $total ?></h3>
            <p>Total Mahasiswa</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose">💯</div>
        <div class="stat-body">
            <h3><?= $total_nil ?></h3>
            <p>Data Nilai</p>
        </div>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div id="form-tambah" class="form-card" style="display:<?= $show_form && !$edit_data ? 'block' : 'none' ?>;">
    <div class="form-card-title">✨ Tambah Data Mahasiswa Baru</div>
    <div class="form-card-subtitle">Isi semua field di bawah untuk menambahkan mahasiswa baru</div>

    <form method="POST" action="querymhs.php">
        <input type="hidden" name="action" value="insert">

        <div class="form-row">
            <div class="form-group">
                <label>NIM (Nomor Induk Mahasiswa)</label>
                <input type="text" name="nim" placeholder="Masukkan NIM mahasiswa" required
                    value="<?= isset($_POST['nim']) && $show_form && !$edit_data ? htmlspecialchars($_POST['nim']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Nama Lengkap Mahasiswa</label>
                <input type="text" name="namamhs" placeholder="Masukkan nama lengkap mahasiswa" required
                    value="<?= isset($_POST['namamhs']) && $show_form && !$edit_data ? htmlspecialchars($_POST['namamhs']) : '' ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm()">✕ Tutup</button>
        </div>
    </form>
</div>

<?php if ($edit_data): ?>
    <div class="form-card">
        <div class="form-card-title">✏️ Edit Data Mahasiswa</div>
        <div class="form-card-subtitle">Ubah data yang ingin diperbaiki, lalu klik Update</div>

        <form method="POST" action="querymhs.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="old_nim" value="<?= htmlspecialchars($edit_data['nim']) ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" name="nim" required value="<?= htmlspecialchars($edit_data['nim']) ?>">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap Mahasiswa</label>
                    <input type="text" name="namamhs" required value="<?= htmlspecialchars($edit_data['namamhs']) ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">🔄 Update Data</button>
                <a href="querymhs.php" class="btn btn-secondary">✕ Batal</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<div class="table-card">
    <div class="table-header">
        <div>
            <h2>📋 Tabel Mahasiswa</h2>
            <div class="sub">Data mahasiswa yang terdaftar di sistem</div>
        </div>
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari mahasiswa..." oninput="filterTable()">
        </div>
    </div>

    <div class="table-wrap">
        <table id="mhsTable">
            <thead>
                <tr>
                    <th class="center">NO</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mhs_data)): ?>
                    <tr>
                        <td colspan="4">
                            <div class="empty">
                                <div class="icon">📭</div>
                                <h3>Belum Ada Data</h3>
                                <p>Klik tombol "Tambah Mahasiswa" untuk menambahkan data.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($mhs_data as $i => $row): ?>
                        <tr>
                            <td class="td-no center"><?= $i + 1 ?></td>
                            <td><span class="badge badge-blue"><?= htmlspecialchars($row['nim']) ?></span></td>
                            <td><span class="name-cell"><?= htmlspecialchars($row['namamhs']) ?></span></td>
                            <td class="center">
                                <div class="actions">
                                    <a href="querymhs.php?action=edit&nim=<?= urlencode($row['nim']) ?>"
                                        class="btn-action btn-edit" title="Edit">✏️</a>
                                    <button class="btn-action btn-del" title="Hapus"
                                        onclick="confirmDelete('<?= htmlspecialchars($row['nim']) ?>','<?= htmlspecialchars(addslashes($row['namamhs'])) ?>')">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span>Menampilkan <strong><?= $total ?></strong> data mahasiswa</span>
        <span>Basis Data 2026 — Universitas Djuanda</span>
    </div>
</div>

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
        btn.textContent = open ? '✕ Tutup Form' : '＋ Tambah Mahasiswa';
        btn.classList.toggle('active', open);
        if (open) el.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function confirmDelete(nim, nama) {
        document.getElementById('deleteMsg').textContent =
            'Hapus mahasiswa "' + nama + '" (NIM: ' + nim + ')?';
        document.getElementById('deleteBtn').href = 'querymhs.php?action=delete&nim=' + encodeURIComponent(nim);
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
        document.querySelectorAll('#mhsTable tbody tr').forEach(row => {
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