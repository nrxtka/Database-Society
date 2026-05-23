<?php
$link = mysqli_connect('localhost', 'root', '');

if (!mysqli_select_db($link, 'basisdata2026')) {
    die("Gagal Konek ke Database: " . mysqli_error($link));
}
?>