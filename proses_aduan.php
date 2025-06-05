<?php
session_start();
include 'database.php';

// Semak jika pengguna telah log masuk
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Dapatkan data dari borang
$fld_no_unit = $_POST['no_unit'];
$nama_pengadu = $_POST['nama_pengadu'];
$no_tel = $_POST['no_tel'];  
if (!preg_match('/^\d{1,11}$/', $no_tel)) {
    echo "<script>alert('Sila masukkan nombor telefon yang sah.'); window.history.back();</script>";
    exit();
}

$tajuk = $_POST['tajuk'];
$penerangan = $_POST['penerangan'];
$lampiran = ''; // Jika ada fail lampiran
if (isset($_FILES['lampiran'])) {
    $lampiran = $_FILES['lampiran']['name']; 
    move_uploaded_file($_FILES['lampiran']['tmp_name'], "uploads/".$lampiran); // Simpan lampiran ke folder
}
$fld_id_pengguna = $_SESSION['user_id']; // ID pengguna yang dihantar dari sesi login

// Sambung ke database
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!preg_match('/^\d{1,11}$/', $no_tel)) {
    echo "<script>alert('Sila masukkan nombor telefon yang sah.'); window.history.back();</script>";
    exit();
}

// Masukkan data ke dalam jadual aduan
try {
    $stmt = $conn->prepare("INSERT INTO tbl_aduan (fld_no_unit, nama_pengadu, no_tel, tajuk, penerangan, lampiran, fld_id_pengguna) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fld_no_unit, $nama_pengadu, $no_tel, $tajuk, $penerangan, $lampiran, $fld_id_pengguna]);
    
    echo "<script>alert('Aduan berjaya dihantar!'); window.location.href='senarai_aduan.php';</script>";
} catch (PDOException $e) {
    echo "Ralat: " . $e->getMessage();
}
?>