<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aduan = $_POST['id_aduan'];
    $status_tindakan = $_POST['status_tindakan'];

    // Sambung ke database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // Kemaskini status_tindakan dalam tbl_aduan
        $stmt = $conn->prepare("UPDATE tbl_aduan SET status_tindakan = ? WHERE id_aduan = ?");
        $stmt->execute([$status_tindakan, $id_aduan]);

        echo "<script>alert('Status aduan berjaya dikemaskini!'); window.location.href='admin_aduan.php';</script>";
    } catch (PDOException $e) {
        echo "Ralat: " . $e->getMessage();
    }
}
?>