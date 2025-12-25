<?php
session_start();
include "../../config/database.php";

$nama_foto = null;

if (!empty($_FILES['foto']['name'])) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_foto = uniqid() . "." . $ext;
    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        "../../uploads/paslon/" . $nama_foto
    );
}

mysqli_query($conn, "INSERT INTO paslon 
(nama_ketua, nama_wakil, visi, misi, foto)
VALUES (
    '{$_POST['nama_ketua']}',
    '{$_POST['nama_wakil']}',
    '{$_POST['visi']}',
    '{$_POST['misi']}',
    '$nama_foto'
)");

header("Location: index.php");
