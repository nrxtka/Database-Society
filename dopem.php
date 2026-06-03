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

// =============================================
// DELETE
// =============================================
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['nim'])) {
    $nim = $conn->real_escape_string($_GET['nim']);
    $del = $conn->query("DELETE FROM tbl_dopem WHERE nim = '$nim'");
    $message      = $del ? "✅ Data berhasil dihapus." : "❌ Gagal menghapus: " . $conn->error;
    $message_type = $del ? "success" : "error";
}

// =============================================
// INSERT — select dari dropdown
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $nim  = $conn->real_escape_string(trim($_POST['nim']));
    $nid  = $conn->real_escape_string(trim($_POST['nid']));

    if ($nim && $nid) {
        // Cek duplikat
        $cek_dop = $conn->query("SELECT nim FROM tbl_dopem WHERE nim='$nim'");
        if ($cek_dop->num_rows > 0) {
            $message      = "⚠️ NIM $nim sudah terdaftar di DOPEM.";
            $message_type = "warning";
            $show_form    = true;
        } else {
            $ins          = $conn->query("INSERT INTO tbl_dopem (nim, nid) VALUES ('$nim','$nid')");
            $message      = $ins ? "✅ Data berhasil ditambahkan." : "❌ Gagal insert DOPEM: " . $conn->error;
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
// UPDATE — select dari dropdown
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $old_nim = $conn->real_escape_string(trim($_POST['old_nim']));
    $nim     = $conn->real_escape_string(trim($_POST['nim']));
    $nid     = $conn->real_escape_string(trim($_POST['nid']));

    if ($nim && $nid) {
        $upd          = $conn->query("UPDATE tbl_dopem SET nim='$nim', nid='$nid' WHERE nim='$old_nim'");
        $message      = $upd ? "✅ Data berhasil diperbarui." : "❌ Gagal update: " . $conn->error;
        $message_type = $upd ? "success" : "error";
    } else {
        $message      = "⚠️ Semua field wajib diisi.";
        $message_type = "warning";
    }
}

// =============================================
// DATA UNTUK EDIT
// =============================================
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nim'])) {
    $edit_nim = $conn->real_escape_string($_GET['nim']);
    $edit_res = $conn->query("
        SELECT d.nim, m.namamhs, d.nid, ds.namados
        FROM tbl_dopem d
        LEFT JOIN tbl_mhs   m  ON d.nim = m.nim
        LEFT JOIN tbl_dosen ds ON d.nid = ds.nid
        WHERE d.nim = '$edit_nim'
    ");
    if ($edit_res && $edit_res->num_rows > 0) $edit_data = $edit_res->fetch_assoc();
}

// =============================================
// DATA DOPEM (JOIN)
// =============================================
$res_dopem  = $conn->query("
    SELECT d.nim, m.namamhs, d.nid, ds.namados
    FROM   tbl_dopem d
    LEFT JOIN tbl_mhs    m  ON d.nim = m.nim
    LEFT JOIN tbl_dosen  ds ON d.nid = ds.nid
    ORDER BY d.nim ASC
");
$dopem_data = [];
if ($res_dopem) while ($r = $res_dopem->fetch_assoc()) $dopem_data[] = $r;
$total = count($dopem_data);

// Count mhs & dosen
$total_mhs = $conn->query("SELECT COUNT(*) as c FROM tbl_mhs")->fetch_assoc()['c'];
$total_dos = $conn->query("SELECT COUNT(*) as c FROM tbl_dosen")->fetch_assoc()['c'];

// =============================================
// GET LIST MAHASISWA DAN DOSEN UNTUK DROPDOWN
// =============================================
$res_mhs = $conn->query("SELECT nim, namamhs FROM tbl_mhs ORDER BY nim ASC");
$list_mhs = [];
if ($res_mhs) while ($r = $res_mhs->fetch_assoc()) $list_mhs[] = $r;

$res_dos = $conn->query("SELECT nid, namados FROM tbl_dosen ORDER BY nid ASC");
$list_dos = [];
if ($res_dos) while ($r = $res_dos->fetch_assoc()) $list_dos[] = $r;
?>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <h1>🔗 Data DOPEM</h1>
        <p>Manajemen data Dosen Pembimbing Mahasiswa</p>
    </div>
    <div class="topbar-right">
        <span class="badge-count"><?= $total ?> Records</span>
        <button class="btn-add" id="btnTambah" onclick="toggleForm()">＋ Tambah Data</button>
    </div>
</div>

<!-- STATS -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon blue">🔗</div>
        <div class="stat-body">
            <h3><?= $total ?></h3>
            <p>Total Data DOPEM</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon cyan">👨‍🎓</div>
        <div class="stat-body">
            <h3><?= $total_mhs ?></h3>
            <p>Total Mahasiswa</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">👨‍🏫</div>
        <div class="stat-body">
            <h3><?= $total_dos ?></h3>
            <p>Total Dosen</p>
        </div>
    </div>
</div>

<!-- ALERT -->
<?php if ($message): ?>
    <div class="alert <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- FORM TAMBAH -->
<div id="form-tambah" class="form-card" style="display:<?= $show_form ? 'block' : 'none' ?>;">
    <div class="form-card-title">✨ Tambah Data DOPEM</div>
    <div class="form-card-subtitle">Pilih mahasiswa dan dosen untuk membuat relasi DOPEM</div>

    <form method="POST" action="dopem.php">
        <input type="hidden" name="action" value="insert">

        <div class="form-section-label">👨‍🎓 Data Mahasiswa</div>
        <div class="form-row">
            <div class="form-group">
                <label>Pilih Mahasiswa</label>
                <select name="nim" required onchange="updateNamaMhs(this.value)">
                    <option value="">-- Pilih Mahasiswa --</option>
                    <?php foreach ($list_mhs as $mhs): ?>
                        <option value="<?= htmlspecialchars($mhs['nim']) ?>">
                            <?= htmlspecialchars($mhs['nim']) ?> - <?= htmlspecialchars($mhs['namamhs']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nama Mahasiswa (Otomatis)</label>
                <input type="text" id="namamhs_display" disabled placeholder="Nama akan terisi otomatis">
            </div>
        </div>

        <div class="form-section-label">👨‍🏫 Data Dosen</div>
        <div class="form-row">
            <div class="form-group">
                <label>Pilih Dosen Pembimbing</label>
                <select name="nid" required onchange="updateNamaDos(this.value)">
                    <option value="">-- Pilih Dosen --</option>
                    <?php foreach ($list_dos as $dos): ?>
                        <option value="<?= htmlspecialchars($dos['nid']) ?>">
                            <?= htmlspecialchars($dos['nid']) ?> - <?= htmlspecialchars($dos['namados']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nama Dosen (Otomatis)</label>
                <input type="text" id="namados_display" disabled placeholder="Nama akan terisi otomatis">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm()">✕ Tutup</button>
        </div>
    </form>
</div>

<!-- FORM EDIT -->
<?php if ($edit_data): ?>
    <div class="form-card">
        <div class="form-card-title">✏️ Edit Data DOPEM</div>
        <div class="form-card-subtitle">Ubah pilihan mahasiswa dan dosen pembimbing</div>

        <form method="POST" action="dopem.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="old_nim" value="<?= htmlspecialchars($edit_data['nim']) ?>">

            <div class="form-section-label">👨‍🎓 Data Mahasiswa</div>
            <div class="form-row">
                <div class="form-group">
                    <label>Pilih Mahasiswa</label>
                    <select name="nim" required onchange="updateNamaMhsEdit(this.value)">
                        <option value="">-- Pilih Mahasiswa --</option>
                        <?php foreach ($list_mhs as $mhs): ?>
                            <option value="<?= htmlspecialchars($mhs['nim']) ?>" 
                                <?= $mhs['nim'] === $edit_data['nim'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mhs['nim']) ?> - <?= htmlspecialchars($mhs['namamhs']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Mahasiswa (Otomatis)</label>
                    <input type="text" id="namamhs_display_edit" value="<?= htmlspecialchars($edit_data['namamhs'] ?? '') ?>" disabled placeholder="Nama akan terisi otomatis">
                </div>
            </div>

            <div class="form-section-label">👨‍🏫 Data Dosen</div>
            <div class="form-row">
                <div class="form-group">
                    <label>Pilih Dosen Pembimbing</label>
                    <select name="nid" required onchange="updateNamaDosEdit(this.value)">
                        <option value="">-- Pilih Dosen --</option>
                        <?php foreach ($list_dos as $dos): ?>
                            <option value="<?= htmlspecialchars($dos['nid']) ?>" 
                                <?= $dos['nid'] === $edit_data['nid'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dos['nid']) ?> - <?= htmlspecialchars($dos['namados']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Dosen (Otomatis)</label>
                    <input type="text" id="namados_display_edit" value="<?= htmlspecialchars($edit_data['namados'] ?? '') ?>" disabled placeholder="Nama akan terisi otomatis">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">🔄 Update Data</button>
                <a href="dopem.php" class="btn btn-secondary">✕ Batal</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- TABEL -->
<div class="table-card">
    <div class="table-header">
        <div>
            <h2>📋 Tabel DOPEM</h2>
            <div class="sub">Data gabungan Mahasiswa dan Dosen Pembimbing</div>
        </div>
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari data..." oninput="filterTable()">
        </div>
    </div>

    <div class="table-wrap">
        <table id="dopemTable">
            <thead>
                <tr>
                    <th class="center">NO</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>NID</th>
                    <th>Nama Dosen</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dopem_data)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty">
                                <div class="icon">📭</div>
                                <h3>Belum Ada Data</h3>
                                <p>Klik tombol "Tambah Data" untuk menambahkan data DOPEM.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dopem_data as $i => $row): ?>
                        <tr>
                            <td class="td-no center"><?= $i + 1 ?></td>
                            <td><span class="nim-badge"><?= htmlspecialchars($row['nim']) ?></span></td>
                            <td><span class="name-cell"><?= htmlspecialchars($row['namamhs'] ?? '—') ?></span></td>
                            <td><span class="nid-badge"><?= htmlspecialchars($row['nid']) ?></span></td>
                            <td><span class="name-dosen"><?= htmlspecialchars($row['namados'] ?? '—') ?></span></td>
                            <td class="center">
                                <div class="actions">
                                    <a href="dopem.php?action=edit&nim=<?= urlencode($row['nim']) ?>"
                                        class="btn-action btn-edit" title="Edit">✏️</a>
                                    <button class="btn-action btn-del" title="Hapus"
                                        onclick="confirmDelete('<?= htmlspecialchars($row['nim']) ?>','<?= htmlspecialchars(addslashes($row['namamhs'] ?? '')) ?>')">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span>Menampilkan <strong><?= $total ?></strong> data DOPEM</span>
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
        btn.textContent = open ? '✕ Tutup Form' : '＋ Tambah Data';
        btn.classList.toggle('active', open);
        if (open) el.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function confirmDelete(nim, nama) {
        document.getElementById('deleteMsg').textContent =
            'Hapus data DOPEM untuk mahasiswa "' + nama + '" (NIM: ' + nim + ')?';
        document.getElementById('deleteBtn').href = 'dopem.php?action=delete&nim=' + encodeURIComponent(nim);
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
        document.querySelectorAll('#dopemTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    // Data mahasiswa dan dosen untuk auto-fill
    const dataMhs = <?php echo json_encode($list_mhs); ?>;
    const dataDos = <?php echo json_encode($list_dos); ?>;

    function updateNamaMhs(nim) {
        const mhs = dataMhs.find(m => m.nim === nim);
        document.getElementById('namamhs_display').value = mhs ? mhs.namamhs : '';
    }

    function updateNamaDos(nid) {
        const dos = dataDos.find(d => d.nid === nid);
        document.getElementById('namados_display').value = dos ? dos.namados : '';
    }

    function updateNamaMhsEdit(nim) {
        const mhs = dataMhs.find(m => m.nim === nim);
        document.getElementById('namamhs_display_edit').value = mhs ? mhs.namamhs : '';
    }

    function updateNamaDosEdit(nid) {
        const dos = dataDos.find(d => d.nid === nid);
        document.getElementById('namados_display_edit').value = dos ? dos.namados : '';
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
