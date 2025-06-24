<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_bayaran = $_POST['id_bayaran'];
    $status_bayaran = $_POST['status_bayaran'];

    // Sambung ke database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $stmt = $conn->prepare("UPDATE tbl_bayaran SET status_bayaran = ? WHERE id_bayaran = ?");
        $stmt->execute([$status_bayaran, $id_bayaran]);

        echo "<script>alert('Status bayaran berjaya dikemaskini!'); window.location.href='admin_lihat_bayaran.php';</script>";
    } catch (PDOException $e) {
        echo "Ralat: " . $e->getMessage();
    }
}
?>