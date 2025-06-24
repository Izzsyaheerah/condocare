<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_pelawat = $_GET['id'];

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("DELETE FROM tbl_pelawat WHERE id_pelawat = ?");
    $stmt->execute([$id_pelawat]);
}

header("Location: senarai_pelawat.php"); // Kembali ke senarai
exit();