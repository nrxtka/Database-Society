<table border=1>
<?php
include "koneksi.php"; 


$query = "SELECT * FROM tbl_mhs";
$result = mysqli_query($link, $query);
$i = 0;

while ($data = mysqli_fetch_array($result)) {
    $i++;
    echo "<tr>";
    echo "<td>", $i, "</td>";
    echo "<td>", $data["nim"], "</td>";
    echo "<td>", $data["namamhs"], "</td>";
    echo "</tr>";
}

mysqli_close($link); 
?>
</table>