<?php
session_start();
include "../../config/database.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM users WHERE id=$id");

header("Location: index.php");
