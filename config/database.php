<?php
$conn = mysqli_connect("localhost", "root", "", "project_pemilu");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
