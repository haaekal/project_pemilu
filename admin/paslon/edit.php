<?php
session_start();
include "../../config/database.php";

$id = $_POST['id'];
$foto_lama = $_POST['foto_lama'];
$nama_foto = $foto_lama;

if (!empty($_FILES['foto']['name'])) {
    if ($foto_lama && file_exists("../../uploads/paslon/$foto_lama")) {
        unlink("../../uploads/paslon/$foto_lama");
    }

    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_foto = uniqid() . "." . $ext;
    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        "../../uploads/paslon/" . $nama_foto
    );
}

mysqli_query($conn, "UPDATE paslon SET
    nama_ketua='{$_POST['nama_ketua']}',
    nama_wakil='{$_POST['nama_wakil']}',
    visi='{$_POST['visi']}',
    misi='{$_POST['misi']}',
    foto='$nama_foto'
    WHERE id=$id
");

header("Location: index.php");
