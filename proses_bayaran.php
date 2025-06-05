<?php
session_start();
$servername = "lrgs.ftsm.ukm.my";
$username = "a195305";
$password = "largeredcat";
$dbname = "a195305";

// // Admin je boleh masuk
// if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] !== 'Pengurusan') {
//     echo "<script>alert('Akses tidak dibenarkan'); window.location.href='login.php';</script>";
//     exit();
// }

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_bayaran = $_POST['jenis_bayaran'];
    $jumlah = $_POST['jumlah'];
    $tarikh_bayaran = $_POST['tarikh_bayaran'];
    $status_bayaran = 'Belum Dibayar';
    $fld_id_pengguna = $_POST['fld_id_pengguna']; // ✅ Ambil dari dropdown borang

    try {
        $stmt = $conn->prepare("INSERT INTO tbl_bayaran (jenis_bayaran, jumlah, tarikh_bayaran, status_bayaran, fld_id_pengguna) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$jenis_bayaran, $jumlah, $tarikh_bayaran, $status_bayaran, $fld_id_pengguna]);

        echo "<script>
            alert('Bayaran berjaya ditambah!');
            window.location.href = 'admin_lihat_bayaran.php';
        </script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Ralat: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>