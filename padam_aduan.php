<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_aduan = $_GET['id'];

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("DELETE FROM tbl_aduan WHERE id_aduan = ?");
    $stmt->execute([$id_aduan]);
}

header("Location: senarai_aduan.php"); // Kembali ke senarai
exit();