<?php
session_start();
include "../../config/database.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$id      = $_POST['id'];
$inisial = $_POST['inisial_nama'];
$nim     = $_POST['nim'];
$kelas   = $_POST['kelas'];
$password = $_POST['password'];

if ($password != "") {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET
        inisial_nama='$inisial',
        nim='$nim',
        kelas='$kelas',
        password='$hash'
        WHERE id=$id
    ");
} else {
    mysqli_query($conn, "UPDATE users SET
        inisial_nama='$inisial',
        nim='$nim',
        kelas='$kelas'
        WHERE id=$id
    ");
}

header("Location: index.php");
