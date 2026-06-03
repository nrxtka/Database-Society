<?php include "atas.php"; ?>

<div class="topbar">
    <div class="topbar-left">
        <h1>👨‍🏫 Data Dosen</h1>
        <p>Manajemen master data dosen pembimbing</p>
    </div>
    <div class="topbar-right">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'tampil_dosen';
        if ($page === 'tampil_dosen'):
        ?>
        <a href="?page=tambah_dosen" class="btn-add">＋ Tambah Dosen</a>
        <?php endif; ?>
    </div>
</div>

<?php
if ($page == 'edit_dosen') {
    include "edit_dosen.php";

} elseif ($page == 'tambah_dosen') {
    include "tambah_dosen.php";

} elseif ($page == 'hapus_dosen') {
    include "hapus_dosen.php";

} else {
    include "koneksi.php";
    $query  = "SELECT * FROM tbl_dosen ORDER BY nid ASC";
    $result = mysqli_query($link, $query);
    $total  = $result ? mysqli_num_rows($result) : 0;
?>

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
            <?php
            $dopem_count = mysqli_query($link, "SELECT COUNT(*) as c FROM tbl_dopem");
            $dopem_c = $dopem_count ? mysqli_fetch_assoc($dopem_count)['c'] : 0;
            ?>
            <h3><?= $dopem_c ?></h3>
            <p>DOPEM Aktif</p>
        </div>
    </div>
</div>

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
                <?php if ($total === 0): ?>
                <tr>
                    <td colspan="4">
                        <div class="empty">
                            <div class="icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p>Klik tombol "Tambah Dosen" untuk menambahkan data.</p>
                        </div>
                    </td>
                </tr>
                <?php else:
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)):
                        $i++;
                ?>
                <tr>
                    <td class="td-no center"><?= $i ?></td>
                    <td><span class="badge badge-green"><?= htmlspecialchars($row['nid']) ?></span></td>
                    <td><span class="name-cell"><?= htmlspecialchars($row['namados']) ?></span></td>
                    <td class="center">
                        <div class="actions">
                            <a href="?page=edit_dosen&nid=<?= urlencode($row['nid']) ?>"
                               class="btn-action btn-edit" title="Edit">✏️</a>
                            <a href="?page=hapus_dosen&nid=<?= urlencode($row['nid']) ?>"
                               class="btn-action btn-del" title="Hapus"
                               onclick="return confirm('Yakin ingin menghapus dosen ini?')">🗑️</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span>Menampilkan <strong><?= $total ?></strong> data dosen</span>
        <span>Basis Data 2026 — Universitas Djuanda</span>
    </div>
</div>

<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#dosenTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

<?php } ?>

<?php include "bawah.php"; ?>
