<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$halaman_aman = ['login.php', 'logout.php'];
$halaman_sekarang = basename($_SERVER['SCRIPT_NAME']);

if (!in_array($halaman_sekarang, $halaman_aman)) {
    if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
        header("Location: login.php");
        exit;
    }
}
?>