<?php
session_start();
include 'database.php';

// Semak jika parameter ID ada
if (!isset($_GET['id'])) {
    header("Location: admin_lihat_bayaran.php");
    exit();
}

$id_bayaran = $_GET['id'];

try {
    // Sambung ke database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Padam rekod bayaran
    $stmt = $conn->prepare("DELETE FROM tbl_bayaran WHERE id_bayaran = ?");
    $stmt->execute([$id_bayaran]);

    // Redirect balik ke page senarai bayaran
    header("Location: admin_lihat_bayaran.php");
    exit();

} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>