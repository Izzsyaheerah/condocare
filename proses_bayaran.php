<?php
session_start();

include 'database.php';

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
    $bulan_bayaran = $_POST['tarikh_bayaran'];  // contoh: 2025-07
    $bulan_bayaran = $bulan_bayaran . '-01';    // convert ke format YYYY-MM-DD
    $status_bayaran = 'Belum Dibayar';
    $fld_id_pengguna = $_POST['fld_id_pengguna'];

    try {
        // Kalau pilih Semua Penghuni
        if ($fld_id_pengguna == 'ALL') {
            $stmt = $conn->query("SELECT fld_id_pengguna FROM tbl_pengguna WHERE fld_userlevel = 'Pengguna'");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pengguna_id = $row['fld_id_pengguna'];
                $insert = $conn->prepare("INSERT INTO tbl_bayaran (jenis_bayaran, jumlah, tarikh_bayaran, status_bayaran, fld_id_pengguna) VALUES (?, ?, ?, ?, ?)");
                $insert->execute([$jenis_bayaran, $jumlah, $bulan_bayaran, $status_bayaran, $pengguna_id]);
            }
        } 
        // Kalau pilih hanya seorang penghuni
        else {
            $insert = $conn->prepare("INSERT INTO tbl_bayaran (jenis_bayaran, jumlah, tarikh_bayaran, status_bayaran, fld_id_pengguna) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$jenis_bayaran, $jumlah, $bulan_bayaran, $status_bayaran, $fld_id_pengguna]);
        }

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