<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_tempahan = $_GET['id'];

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("DELETE FROM tbl_tempahan WHERE id_tempahan = ?");
    $stmt->execute([$id_tempahan]);
}

header("Location: senarai_tempahan.php"); // Kembali ke senarai
exit();