<?php
session_start();
include 'database.php';

// Pastikan pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Ambil data dari borang
$fld_id_pengguna = $_SESSION['user_id'];
$kemudahan = $_POST['kemudahan'];
$fld_no_unit = $_POST['no_unit'];
$no_tel = $_POST['no_tel'];
$masa_slot = $_POST['masa_tempahan']; // Contoh: "07:00-14:00"
$tarikh_tempahan = $_POST['tarikh_tempahan'];

// Pisahkan masa mula dan tamat
list($masa_mula, $masa_tamat) = explode('-', $masa_slot);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Semak pertindihan tempahan
    $check = $conn->prepare("SELECT * FROM tbl_tempahan 
                             WHERE kemudahan = ? 
                             AND tarikh_tempahan = ? 
                             AND (
                                 (masa_mula <= ? AND masa_tamat > ?) OR
                                 (masa_mula < ? AND masa_tamat >= ?)
                             )");
    $check->execute([
        $kemudahan,
        $tarikh_tempahan,
        $masa_mula, $masa_mula,
        $masa_tamat, $masa_tamat
    ]);

    if ($check->rowCount() > 0) {
        echo "<script>alert('Tempahan gagal! Slot masa ini telah ditempah.'); window.history.back();</script>";
        exit();
    }

    // Simpan tempahan baru
    $stmt = $conn->prepare("INSERT INTO tbl_tempahan 
        (fld_id_pengguna, kemudahan, fld_no_unit, no_tel, masa_mula, masa_tamat, tarikh_tempahan) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $fld_id_pengguna,
        $kemudahan,
        $fld_no_unit,
        $no_tel,
        $masa_mula,
        $masa_tamat,
        $tarikh_tempahan
    ]);

    echo "<script>alert('Tempahan berjaya dihantar!'); window.location.href='dashboard_user.php';</script>";

} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>