<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "laundryfika";

$koneksi = mysqli_connect ($host, $user, $pass, $db);

if (!$koneksi) {
    die("koneksi database gagal: " . mysqli_connect_error());
}

?>