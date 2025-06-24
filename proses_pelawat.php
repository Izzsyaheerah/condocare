<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$tujuan_pelawat = $_POST['tujuan_pelawat'];
$nama_pelawat = $_POST['nama_pelawat'];
$no_tel_pelawat = $_POST['no_tel_pelawat'];
if (!preg_match('/^\d{1,11}$/', $no_tel_pelawat)) {
    echo "<script>alert('Sila masukkan nombor telefon yang sah.'); window.history.back();</script>";
    exit();
}
$no_kenderaan = $_POST['no_kenderaan'];
$tarikh_lawatan = $_POST['tarikh_lawatan'];
$kategori_pelawat = $_POST['kategori_pelawat'];
$fld_id_pengguna = $_SESSION['user_id']; 

// Semak tarikh tempahan dengan tarikh semasa
$current_date = date('Y-m-d'); // Tarikh semasa dalam format YYYY-MM-DD

if ($tarikh_lawatan < $current_date) {
    echo "<script>alert('Tarikh tempahan telah lepas. Sila pilih tarikh yang akan datang.'); window.history.back();</script>";
    exit();
}

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!preg_match('/^\d{1,11}$/', $no_tel_pelawat)) {
    echo "<script>alert('Sila masukkan nombor telefon yang sah.'); window.history.back();</script>";
    exit();
}

try {
    $stmt = $conn->prepare("SELECT id_pelawat FROM tbl_pelawat ORDER BY id_pelawat DESC LIMIT 1");
    $stmt->execute();
    $last_id = $stmt->fetchColumn(); 

    if ($last_id) {
        $last_number = (int)substr($last_id, 2); 
        $new_id = 'VS' . ($last_number + 1); 
    } else { 
        $new_id = 'VS1'; 
    }

    $stmt = $conn->prepare("INSERT INTO tbl_pelawat (id_pelawat, tujuan_pelawat, nama_pelawat, no_tel_pelawat, no_kenderaan, tarikh_lawatan, kategori_pelawat, fld_id_pengguna) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$new_id, $tujuan_pelawat, $nama_pelawat, $no_tel_pelawat, $no_kenderaan, $tarikh_lawatan, $kategori_pelawat, $fld_id_pengguna]);

    echo "<script>alert('Pelawat berjaya ditambah!'); window.location.href='lihat_pelawat.php?id=$new_id';</script>";

} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>