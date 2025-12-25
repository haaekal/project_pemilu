<?php
session_start();
include "../../config/database.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT foto FROM paslon WHERE id=$id")
);

if ($data['foto'] && file_exists("../../uploads/paslon/".$data['foto'])) {
    unlink("../../uploads/paslon/".$data['foto']);
}

mysqli_query($conn, "DELETE FROM paslon WHERE id=$id");

header("Location: index.php");
