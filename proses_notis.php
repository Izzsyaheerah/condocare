<?php
session_start();

// Sambung ke database
$servername = "lrgs.ftsm.ukm.my";
$username = "a195305";
$password = "largeredcat";
$dbname = "a195305";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Gagal sambung ke pangkalan data: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tajuk = $_POST['tajuk'];
    $kandungan = $_POST['kandungan'];

    try {
        $stmt = $conn->prepare("INSERT INTO tbl_notis (tajuk, kandungan) VALUES (?, ?)");
        $stmt->execute([$tajuk, $kandungan]);

        $_SESSION['notis_status'] = "Notis berjaya dihantar!";
    } catch (PDOException $e) {
        $_SESSION['notis_status'] = "Ralat: " . $e->getMessage();
    }
}

header("Location: dashboard_admin.php");
exit();
?>
