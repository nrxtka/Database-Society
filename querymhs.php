<?php include "atas.php"; ?>

<div class="topbar">
    <div class="topbar-left">
        <h1>👥 Data Mahasiswa</h1>
        <p>Master data seluruh mahasiswa terdaftar</p>
    </div>
    <div class="topbar-right">
        <?php
        include "koneksi.php";
        $total_res = mysqli_query($link, "SELECT COUNT(*) as c FROM tbl_mhs");
        $total = $total_res ? mysqli_fetch_assoc($total_res)['c'] : 0;
        ?>
        <span class="badge-count"><?= $total ?> Mahasiswa</span>
    </div>
</div>

<!-- STATS -->
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
            <?php
            $nil_res = mysqli_query($link, "SELECT COUNT(*) as c FROM tbl_nilai");
            $total_nil = $nil_res ? mysqli_fetch_assoc($nil_res)['c'] : 0;
            ?>
            <h3><?= $total_nil ?></h3>
            <p>Data Nilai</p>
        </div>
    </div>
</div>

<!-- TABLE -->
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
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM tbl_mhs ORDER BY nim ASC";
                $result = mysqli_query($link, $query);
                $i = 0;

                if ($result && mysqli_num_rows($result) > 0):
                    while ($data = mysqli_fetch_assoc($result)):
                        $i++;
                ?>
                <tr>
                    <td class="td-no center"><?= $i ?></td>
                    <td><span class="badge badge-blue"><?= htmlspecialchars($data['nim']) ?></span></td>
                    <td><span class="name-cell"><?= htmlspecialchars($data['namamhs']) ?></span></td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="3">
                        <div class="empty">
                            <div class="icon">📭</div>
                            <h3>Belum Ada Data</h3>
                            <p>Belum ada data mahasiswa yang terdaftar.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span>Menampilkan <strong><?= $i ?></strong> data mahasiswa</span>
        <span>Basis Data 2026 — Universitas Djuanda</span>
    </div>
</div>

<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#mhsTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

<?php include "bawah.php"; ?>
