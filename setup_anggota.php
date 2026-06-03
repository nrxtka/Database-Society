<?php
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "basisdata2026";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Buat tabel jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS tbl_anggota (
    Username VARCHAR(100) NOT NULL,
    NIM VARCHAR(20) PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql)) {
    echo "✅ Tabel tbl_anggota siap<br>";
} else {
    echo "❌ Error: " . $conn->error . "<br>";
}

// Cek apakah sudah ada data
$result = $conn->query("SELECT COUNT(*) as c FROM tbl_anggota");
$count = $result->fetch_assoc()['c'];

if ($count == 0) {
    // Import data awal
    $data = [
        ['Muhammad Zibril Tafa Tabia', 'I.2510056'],
        ['Firman Maulana', 'I.2510194'],
        ['Nidia Nur Artika', 'I.2510256'],
        ['Indria Nurputri Pratiwi', 'I.2510503'],
        ['Muhammad Farhan Fauzi Akbar', 'I.2510513'],
        ['Abduroffi Thoriq Alfaruq', 'I.2510736']
    ];
    
    foreach ($data as $item) {
        $username = $conn->real_escape_string($item[0]);
        $nim = $conn->real_escape_string($item[1]);
        $sql = "INSERT INTO tbl_anggota (Username, NIM) VALUES ('$username', '$nim')";
        $conn->query($sql);
    }
    
    echo "✅ Data awal berhasil diimport (6 anggota)<br>";
} else {
    echo "ℹ️ Tabel sudah punya $count data<br>";
}

echo "<br>";
echo "<h2>Data di tbl_anggota:</h2>";
$result = $conn->query("SELECT * FROM tbl_anggota ORDER BY NIM");
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>No</th><th>NIM</th><th>Nama</th></tr>";
$no = 1;
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>$no</td><td>{$row['NIM']}</td><td>{$row['Username']}</td></tr>";
    $no++;
}
echo "</table>";

echo "<br><a href='anggota.php'>👈 Kembali ke anggota.php</a>";

$conn->close();
?>
