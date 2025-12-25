<?php
session_start();
include "../../config/database.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$inisial = $_POST['inisial_nama'];
$nim     = $_POST['nim'];
$kelas   = $_POST['kelas'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($conn, "INSERT INTO users 
(inisial_nama, nim, kelas, password, role)
VALUES ('$inisial','$nim','$kelas','$password','user')");

header("Location: index.php");
